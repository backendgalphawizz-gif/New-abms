<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CategoryManager;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\ProductManager;
use App\Http\Controllers\Controller;
use App\Model\Brand;
use App\Model\Category;
use App\Model\Color;
use App\Model\FlashDeal;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\ShippingMethod;
use App\Model\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class ProductController extends Controller
{
    public function get_latest_products(Request $request)
    {
        $products = ProductManager::get_latest_products($request['limit'], $request['offset']);
        
        $products['products'] = Helpers::product_data_formatting($products['products'], true, $request->user()->id ?? "");

        foreach ($products['products'] as &$product) {
            if (!empty($product['selling_price'])) {
                $priceStr = $product['selling_price'];
                $numericPrice = (float) filter_var($priceStr, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $product['selling_price'] = (int) round($numericPrice);
            }
        }

        return response()->json($products, 200);
    }
    public function get_product_for_you(Request $request)
    {
        $products = ProductManager::get_product_for_you($request['limit'], $request['offset']);
        
        $products['products'] = Helpers::product_data_formatting($products['products'], true, $request->user()->id ?? "");

        foreach ($products['products'] as &$product) {
            if (!empty($product['selling_price'])) {
                $priceStr = $product['selling_price'];
                $numericPrice = (float) filter_var($priceStr, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $product['selling_price'] = (int) round($numericPrice);
            }
        }

        return response()->json($products, 200);
    }

    public function all_products(Request $request)
    {

        $params = [];
        if ($request->get('category_id') && $request->get('category_id') != "") {
            $params['category_id'] = $request->get('category_id');
        }

        if ($request->get('sub_category_id') && $request->get('sub_category_id') != "") {
            $params['sub_category_id'] = $request->get('sub_category_id');
        }

        if ($request->get('brand_id') && $request->get('brand_id') != "") {
            $params['brand_id'] = $request->get('brand_id');
        }

        if ($request->get('vendor_id') && $request->get('vendor_id') != "") {
            $params['user_id'] = $request->get('vendor_id');
            $params['added_by'] = 'seller';
        }

        $filters = [];
        if ($request->get('min_price') && $request->get('min_price') != "" && $request->get('max_price') && $request->get('max_price') != "") {
            $filters['min_price'] = $request->get('min_price');
            $filters['max_price'] = $request->get('max_price');
        }

        if ($request->get('review_filter') && $request->get('review_filter') != "") {
            $filters['review_filter'] = $request->get('review_filter');
        }

        $productids = [];
        if ($request->get('offer_id') && $request->get('offer_id') != "") {
            $offerid = $request->get('offer_id');
            $flash = FlashDeal::find($offerid);
            $productids = $flash->products->pluck('product_id');
        }

        if ($request->get('search_text') && $request->get('search_text') != "") {
            $filters['search_text'] = $request->get('search_text');
        }

        $sort['sort_by'] = $request->get('sort_by') ?? 'price';
        $sort['order_by'] = $request->get('order_by') ?? 'DESC';

        $products = ProductManager::get_all_products($request['limit'], $request['offset'], $params, $sort, $filters, $productids);
        $user_id = $request->input('user_id') ?? "";
        $products['products'] = Helpers::product_data_formatting($products['products'], true, $user_id) ?? [];
        return response()->json($products, 200);
    }

    public function filters(Request $request)
    {

        $brands = Brand::get();
        $products = Product::select('colors')->where('colors', '!=', '[]')->get();
        $colorCodes = [];
        if (!empty($products)) {
            foreach ($products as $product) {
                foreach (json_decode($product->colors, true) as $ckey => $color) {
                    $colorCodes[] = $color;
                }
            }
        }

        $colorFilters = [];
        $colors = Color::whereIn('code', $colorCodes)->get();
        if (!empty($colors)) {
            foreach ($colors as $bKey => $color) {
                $colorFilters[$bKey]['title'] = $color->code;
                $colorFilters[$bKey]['value'] = $color->code;
            }
        }

        $brandFilters = [];
        if (!empty($brands)) {
            foreach ($brands as $bKey => $brand) {
                $brandFilters[$bKey]['title'] = $brand->name;
                $brandFilters[$bKey]['value'] = strval($brand->id);
            }
        }

        $filters = [];
        $filters[] = [
            'title' => 'Color',
            'fields' => $colorFilters
        ];

        $filters[] = [
            'title' => 'Price',
            'fields' => [
                [
                    'title' => 'Min',
                    'value' => '1'
                ],
                [
                    'title' => 'Max',
                    'value' => '1000'
                ]
            ]
        ];

        $filters[] = [
            'title' => 'Brand',
            'fields' => $brandFilters
        ];

        $filters[] = [
            'title' => 'Rating',
            'fields' => [
                [
                    'title' => '2.0+',
                    'value' => '2'
                ],
                [
                    'title' => '3.0+',
                    'value' => '3'
                ],
                [
                    'title' => '3.5+',
                    'value' => '3.5'
                ],
                [
                    'title' => '4.0+',
                    'value' => '4'
                ],
                [
                    'title' => '4.5+',
                    'value' => '4.5'
                ]
            ]
        ];
        $response = ['status' => true, 'message' => 'Product Filters', 'data' => $filters];
        return response()->json($response, 200);
    }

    public function get_featured_products(Request $request)
    {
        $products = ProductManager::get_featured_products($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        return response()->json($products, 200);
    }

    public function get_top_rated_products(Request $request)
    {
        $products = ProductManager::get_top_rated_products($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        return response()->json($products, 200);
    }

    public function get_searched_products(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $products = ProductManager::search_products($request['name'], 'all', $request['limit'], $request['offset']);
        if ($products['products'] == null) {
            $products = ProductManager::translated_product_search($request['name'], 'all', $request['limit'], $request['offset']);
        }
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        return response()->json($products, 200);
    }

    public function get_product(Request $request, $slug)
    {
        $product = Product::with(['reviews.customer', 'seller.shop', 'tags'])->where(['slug' => $slug])->first();
        $user_id = request('user_id') ?? "";
        $relatedProducts = [];
        if (isset($product)) {
            $category_ids = $product['category_ids'];
            $product['reviews'] = Review::where('product_id', $product->id)->get();
            $product['reviews_count'] = Review::where('product_id', $product->id)->count();
            $product = Helpers::product_data_formatting($product, false, $request->user()->id ?? "");

            // $product['reviews'] = Helpers::set_review_data($product['reviews'], false, $user_id);
            if (isset($product['reviews']) && !empty($product['reviews'])) {
                $overallRating = \App\CPU\ProductManager::get_overall_rating(json_decode(json_encode($product['reviews'])));
                $product['average_review'] = $overallRating[0];
            } else {
                $product['average_review'] = 0;
            }

            $temporary_close = Helpers::get_business_settings('temporary_close');
            $inhouse_vacation = Helpers::get_business_settings('vacation_add');
            $inhouse_vacation_start_date = isset($product['added_by']) && $product['added_by'] == 'admin' ? $inhouse_vacation['vacation_start_date'] : "";
            $inhouse_vacation_end_date = isset($product['added_by']) && $product['added_by'] == 'admin' ? $inhouse_vacation['vacation_end_date'] : "";
            $inhouse_temporary_close = isset($product['added_by']) && $product['added_by'] == 'admin' ? $temporary_close['status'] : false;
            $product['inhouse_vacation_start_date'] = $inhouse_vacation_start_date;
            $product['inhouse_vacation_end_date'] = $inhouse_vacation_end_date;
            $product['inhouse_temporary_close'] = $inhouse_temporary_close;
            $relatedProducts = Product::active()->where('category_ids', $category_ids)->where('id', '!=', $product['id'])->limit(12)->get();

            $relatedProducts = Helpers::product_data_formatting($relatedProducts, true, $user_id);
        }

        $product = isset($product) && !is_null($product) ? Helpers::product_data_price_format($product) : [];
        $relatedProducts = isset($relatedProducts) && is_array($relatedProducts) ? Helpers::product_data_price_format($relatedProducts) : [];

        if (!empty($product['selling_price'])) {
            $priceStr = $product['selling_price'];
            $numericPrice = (int) filter_var($priceStr, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $product['selling_price'] = (int) round($numericPrice);
        }

        if (!empty($relatedProducts) && is_array($relatedProducts)) {
            foreach ($relatedProducts as $key => $item) {
                if (!empty($item['selling_price'])) {
                    $priceStr = $item['selling_price'];
                    $numericPrice = (float) filter_var($priceStr, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $relatedProducts[$key]['selling_price'] = (int) round($numericPrice);
                }
            }
        }

        $response = [
            'status' => true,
            'message' => 'Product details',
            'data' => [$product],
            'related_products' => $relatedProducts
        ];

        return response()->json($response, 200);
    }

    public function get_best_sellings(Request $request)
    {
        $products = ProductManager::get_best_selling_products($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);

        return response()->json($products, 200);
    }

    public function get_home_categories()
    {
        $categories = Category::where('home_status', true)->get();
        $categories->map(function ($data) {
            $data['products'] = Helpers::product_data_formatting(CategoryManager::products($data['id']), true);
            return $data;
        });
        return response()->json($categories, 200);
    }

    public function get_related_products($id)
    {
        if (Product::find($id)) {
            $products = ProductManager::get_related_products($id);
            $products = Helpers::product_data_formatting($products, true);
            return response()->json($products, 200);
        }
        return response()->json([
            'errors' => ['code' => 'product-001', 'message' => translate('Product not found!')]
        ], 404);
    }

    public function get_product_reviews($id)
    {
        $reviews = Review::with(['customer'])->where(['product_id' => $id])->get();

        $storage = [];
        foreach ($reviews as $item) {
            $item['attachment'] = json_decode($item['attachment']);
            array_push($storage, $item);
        }

        return response()->json($storage, 200);
    }

    public function get_product_rating($id)
    {
        try {
            $product = Product::find($id);
            $overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews);
            return response()->json(floatval($overallRating[0]), 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function counter($product_id)
    {
        try {
            $countOrder = OrderDetail::where('product_id', $product_id)->count();
            $countWishlist = Wishlist::where('product_id', $product_id)->count();
            return response()->json(['order_count' => $countOrder, 'wishlist_count' => $countWishlist], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function social_share_link($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $link = route('product', $product->slug);
        try {

            return response()->json($link, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function submit_product_review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'comment' => 'required',
            'rating' => 'required',
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

        Review::updateOrCreate(
            [
                'delivery_man_id' => '',
                'customer_id' => $request->user()->id,
                'product_id' => $request->product_id
            ],
            [
                'customer_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'comment' => $request->comment,
                'rating' => $request->rating,
                'order_id' => $request->order_id ?? "",
                'attachment' => json_encode($image_array),
            ]
        );

        return response()->json(['status' => true, 'message' => translate('successfully review submitted!'), 'data' => []], 200);
    }

    public function submit_deliveryman_review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'comment' => 'required',
            'rating' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $order = Order::where([
            'id' => $request->order_id,
            'customer_id' => $request->user()->id,
            'payment_status' => 'paid'
        ])->first();

        if (!isset($order->delivery_man_id)) {
            return response()->json(['message' => translate('Invalid review!')], 403);
        }

        Review::updateOrCreate(
            [
                'delivery_man_id' => $order->delivery_man_id,
                'customer_id' => $request->user()->id,
                'order_id' => $order->id
            ],
            [
                'customer_id' => $request->user()->id,
                'order_id' => $order->id,
                'delivery_man_id' => $order->delivery_man_id,
                'comment' => $request->comment,
                'rating' => $request->rating,
            ]
        );

        return response()->json(['message' => translate('successfully review submitted!')], 200);
    }

    public function get_shipping_methods(Request $request)
    {
        $methods = ShippingMethod::where(['status' => 1])->get();
        return response()->json($methods, 200);
    }

    public function get_discounted_product(Request $request)
    {
        $products = ProductManager::get_discounted_product($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        return response()->json($products, 200);
    }

    public function check_pincode(Request $request)
    {
        $pincode = $request->input('pincode');
        $response = [
            'status' => true,
            'message' => 'Delivery Available Pincode',
            'data' => []
        ];
        return response()->json($response);
    }

    public function delete_review(Request $request)
    {
        $id = $request->input('id');
        $review = Review::where('order_id', $id)->first();
        if ($review && $review->delete()) {
            $response = [
                'status' => true,
                'message' => 'Review deleted success',
                'data' => []
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Review not found',
                'data' => []
            ];
        }
        return response()->json($response);
    }

    public function delete_review_by_id(Request $request)
    {
        $id = $request->input('id');
        $review = Review::find($id);
        if ($review && $review->delete()) {
            $response = [
                'status' => true,
                'message' => 'Review deleted success',
                'data' => []
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Review not found',
                'data' => []
            ];
        }
        return response()->json($response);
    }
}
