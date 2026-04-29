<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\CPU\ProductManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use Illuminate\Http\Request;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\ShopFollower;
use Carbon\Carbon;

class SellerController extends Controller
{
    public function __construct(
        private ShopFollower $shop_follower,
    ) {}

    public function get_seller_info(Request $request)
    {
        $data = [];
        $seller = Seller::with(['shop'])->where(['id' => $request['seller_id']])->first(['id', 'f_name', 'l_name', 'phone', 'image']);

        $product_ids = Product::where(['added_by' => 'seller', 'user_id' => $request['seller_id']])->active()->pluck('id')->toArray();

        $avg_rating = Review::whereIn('product_id', $product_ids)->avg('rating');
        $total_review = Review::whereIn('product_id', $product_ids)->count();
        $total_order = OrderDetail::whereIn('product_id', $product_ids)->groupBy('order_id')->count();
        $total_product = Product::active()->where(['added_by' => 'seller', 'user_id' => $request['seller_id']])->count();

        $data['seller'] = $seller;
        $data['avg_rating'] = round($avg_rating);
        $data['total_review'] =  $total_review;
        $data['total_order'] = $total_order;
        $data['total_product'] = $total_product;

        return response()->json($data, 200);
    }

    public function get_seller_products($seller_id, Request $request)
    {
        $data = ProductManager::get_seller_products($seller_id, $request);
        $data['products'] = Helpers::product_data_formatting($data['products'], true);
        return response()->json($data, 200);
    }

    public function get_seller_all_products($seller_id, Request $request)
    {
        $products = Product::active()
            ->with(['rating', 'tags'])
            ->where(['user_id' => $seller_id, 'added_by' => 'seller'])
            ->when($request->search, function ($query) use ($request) {
                $key = explode(' ', $request->search);
                foreach ($key as $value) {
                    $query->where('name', 'like', "%{$value}%");
                }
            })
            ->latest()
            ->paginate($request->limit, ['*'], 'page', $request->offset);


        $products_final = Helpers::product_data_formatting($products->items(), true);

        return [
            'total_size' => $products->total(),
            'limit' => (int)$request->limit,
            'offset' => (int)$request->offset,
            'products' => $products_final
        ];
    }

    public function get_top_sellers()
    {
        $top_sellers = Shop::whereHas('seller', function ($query) {
            return $query->approved();
        })
            ->take(15)->get();
        $top_sellers = $top_sellers->map(function ($data) {
            $data['seller_id'] = (int)$data['seller_id'];
            return $data;
        });
        return response()->json($top_sellers, 200);
    }

    // public function get_all_sellers() {
    //     $top_sellers = Shop::withCount('product')->whereHas('seller',function ($query){return $query->approved();})->get();
    //     dd($top_sellers);
    //     $top_sellers = $top_sellers->map(function($data){
    //         return Helpers::set_shop_data($data);
    //     });

    //     $response = ['status' => true, 'message' => 'All vendor list', 'data' => $top_sellers];

    //     return response()->json($response, 200);
    // }

    public function get_all_sellers()
    {
        $top_sellers = Shop::with([
            'seller',
            'product.brand' // Eager load products with their brand
        ])
            ->withCount('product')
            ->whereHas('seller', function ($query) {
                return $query->approved();
            })
            ->get();

        $top_sellers = $top_sellers->map(function ($shop) {
            $shop_data = Helpers::set_shop_data($shop);

            // Seller name
            if ($shop->seller) {
                $shop_data['seller_name'] = $shop->seller->f_name . ' ' . $shop->seller->l_name;
            }

            $brands = $shop->product
                ->whereNotNull('brand') 
                ->pluck('brand')
                ->unique('id') 
                ->values()
                ->map(function ($brand) {
                    return [
                        'id' => $brand->id,
                        'name' => $brand->name,
                        'image' => url('storage/app/public/brand/' . $brand->image),

                    ];
                });

            $shop_data['brands'] = $brands;

            return $shop_data;
        });

        return response()->json([
            'status' => true,
            'message' => 'All vendor list',
            'data' => $top_sellers
        ], 200);
    }



    public function follow_seller(Request $request)
    {

        if (auth()->check()) {
            $shopFollower = $this->shop_follower->where(['user_id' => auth()->user()->id, 'shop_id' => $request->shop_id])->first();
            if ($shopFollower) {
                $shopFollower->delete();
                $followers = $this->shop_follower->where(['shop_id' => $request->shop_id])->count();

                return response()->json([
                    'status' => true,
                    'message' => translate("unfollow_successfully") . "!"
                ]);
            } else {
                $this->shop_follower->insert([
                    'user_id' => auth()->user()->id,
                    'shop_id' => $request->shop_id,
                    'created_at' => Carbon::now(),
                ]);
                $followers = $this->shop_follower->where(['shop_id' => $request->shop_id])->count();

                return response()->json([
                    'status' => true,
                    'message' => translate("follow_successfully") . "!"
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => translate("Login_first")
            ]);
        }
    }
}
