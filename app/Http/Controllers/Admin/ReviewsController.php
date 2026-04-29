<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\CPU\Helpers;
use App\Model\Review;
use App\Model\Product;
use App\Model\Order;
use App\CPU\ProductManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use App\CPU\ImageManager;

class ReviewsController extends Controller
{
    function list(Request $request)
    {
        $query_param = [];
        if (!empty($request->from) && empty($request->to)) {
            Toastr::warning('Please select to date!');
        }
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $product_id = Product::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->where('name', 'like', "%{$value}%");
                }
            })->pluck('id')->toArray();
            $customer_id = User::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%");
                }
            })->pluck('id')->toArray();
            $reviews = Review::WhereIn('product_id',  $product_id)->orWhereIn('customer_id', $customer_id);
            $query_param = ['search' => $request['search']];
        } else {
            $reviews = Review::with(['product', 'customer'])
                ->when($request->product_id != null, function ($q) {
                    $q->where('product_id', request('product_id'));
                })->when($request->customer_id != null, function ($q) {
                    $q->where('customer_id', request('customer_id'));
                })->when($request->status != null, function ($q) {
                    $q->where('status', request('status'));
                })->when($request->from && $request->to, function ($q) use ($request) {
                    $q->whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
                });
        }
        $reviews = $reviews->latest('created_at')->paginate(Helpers::pagination_limit());
        $products = Product::whereNotIn('request_status', [0])->select('id', 'name')->get();
        $customers = User::whereNotIn('id', [0])->select('id', 'name', 'f_name', 'l_name')->get();
        $customer_id = $request['customer_id'];
        $product_id = $request['product_id'];
        $status = $request['status'];
        $from = $request->from;
        $to = $request->to;

        return view('admin-views.reviews.list', compact('reviews', 'search', 'products', 'customers', 'from', 'to', 'customer_id', 'product_id', 'status'));
    }
    public function export(Request $request)
    {

        $product_id = $request['product_id'];
        $customer_id = $request['customer_id'];
        $status = $request['status'];
        $from = $request['from'];
        $to = $request['to'];



        $data = Review::with(['product', 'customer'])
            ->when($product_id != null, function ($q) use ($request) {
                $q->where('product_id', $request['product_id']);
            })
            ->when($customer_id != null, function ($q) use ($request) {
                $q->where('customer_id', $request['customer_id']);
            })
            ->when($status != null, function ($q) use ($request) {
                $q->where('status', $request['status']);
            })
            ->when($to != null && $from != null, function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
            })
            ->get();



        if ($data->count() == 0) {

            Toastr::warning('No data found for export!');
            return back();
        }

        return (new FastExcel(ProductManager::export_product_reviews($data)))->download('Review' . date('d_M_Y') . '.xlsx');
    }
    public function status(Request $request)
    {
        $review = Review::find($request->id);
        $review->status = $request->status;
        $review->save();
        Toastr::success('Review status updated!');
        return back();
    }

    public function create()
    {
        $orders = Order::select('id')->get();
        $products = Product::select('id', 'name')->get();
        // dd($orders, "here", $products);
        return view('admin-views.reviews.create', compact('orders', 'products'));
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        $products = Product::whereNotIn('request_status', [0])->select('id', 'name')->get();
        $orders = Order::select('id')->latest()->get();

        return view('admin-views.reviews.create', compact('review', 'products', 'orders'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $image_array = [];

        if (!empty($request->file('fileUpload'))) {
            foreach ($request->file('fileUpload') as $image) {
                if ($image != null) {
                    array_push($image_array, ImageManager::upload('review/', 'png', $image));
                }
            }
        }

        $user_image_path = null;
        if ($request->hasFile('user_image')) {
            $user_image_path = ImageManager::upload('profile/', 'png', $request->file('user_image'));
        }

        $review = $request->id ? Review::find($request->id) : new Review();

        if (!$review) {
            Toastr::error('Review not found!');
            return redirect()->back();
        }

        $review->customer_id = -1;
        $review->product_id = $request->product_id;
        $review->order_id = $request->order_id ?? null;
        $review->comment = $request->comment;
        $review->user_name = $request->user_name;

        $existing_attachments = $request->has('existing_attachments') ? $request->existing_attachments : [];

        $all_attachments = array_merge($existing_attachments, $image_array);

        if (!empty($all_attachments)) {
            $review->attachment = json_encode($all_attachments);
        } else {
            $review->attachment = null;
        }

        $review->rating = $request->rating;
        $review->status = 1;
        $review->is_saved = 0;
        $review->fake_review = 1;
        $review->delivery_man_id = null;

        if ($user_image_path) {
            $review->user_image = $user_image_path;
        }

        $review->save();

        Toastr::success($request->id ? 'Review updated successfully!' : 'Review added successfully!');
        return redirect()->route('admin.reviews.list');
    }


    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'product_id' => 'required|integer',
    //         'comment' => 'required|string',
    //         'rating' => 'required|integer|min:1|max:5',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => Helpers::error_processor($validator)], 403);
    //     }

    //     $image_array = [];

    //     // Upload review images
    //     if (!empty($request->file('fileUpload'))) {
    //         foreach ($request->file('fileUpload') as $image) {
    //             if ($image != null) {
    //                 array_push($image_array, ImageManager::upload('review/', 'png', $image));
    //             }
    //         }
    //     }

    //     // Upload user profile image if provided
    //     $user_image_path = null;
    //     if ($request->hasFile('user_image')) {
    //         $user_image_path = ImageManager::upload('profile/', 'png', $request->file('user_image'));
    //     }

    //     $review = new Review();

    //     $review->customer_id = -1;
    //     $review->product_id = $request->product_id;
    //     $review->order_id = $request->order_id ?? null;
    //     $review->comment = $request->comment;
    //     $review->user_name = $request->user_name;
    //     $review->attachment = json_encode($image_array);
    //     $review->rating = $request->rating;
    //     $review->status = 1;
    //     $review->is_saved = 0;
    //     $review->fake_review = 1;
    //     $review->delivery_man_id = null;
    //     $review->user_image = $user_image_path;

    //     $review->save();


    //     Toastr::success('Review added successfully!');
    //     return redirect()->route('admin.reviews.list');
    // }
}
