<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Convert;
use App\CPU\Helpers;
use App\Model\BusinessSetting;
use App\Model\Color;
use App\Model\Product;
use PHPUnit\Exception;
use App\CPU\ImageManager;
use App\Model\Translation;
use App\Model\DealOfTheDay;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\FlashDealProduct;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\PDF;
use App\CPU\BackEndHelper;

class ProductController extends Controller
{
    public function list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        if ($request->input('status') == 'all') {
            $prods = Product::where(['added_by' => 'seller', 'user_id' => $seller['id']])->orderBy('id', 'DESC');
        } else {
            $prods = Product::where(['added_by' => 'seller', 'user_id' => $seller['id'], 'status' => $request->input('status')])->orderBy('id', 'DESC');
        }

        if ($request->input('type') == 'low_stock') {
            $prods = $prods->where('current_stock', '<', 4);
        } else if ($request->input('type') == 'out_of_stock') {
            $prods = $prods->where('current_stock', '<', 1);
        }

        // if($request->input('category_id') && $request->input('sub_category_id') && $request->input('category_id') != "" && $request->input('sub_category_id') != "") {
        //     $prods = $prods->where(['category_id' => $request->input('category_id'), 'sub_category_id' => $request->input('sub_category_id')]);
        // }

        if ($request->filled('category_id')) {
            $prods = $prods->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('brand_id')) {
            $prods = $prods->where('brand_id', $request->input('brand_id'));
        }

        // ✅ Apply sub-category filter if passed
        if ($request->filled('sub_category_id')) {
            $prods = $prods->where('sub_category_id', $request->input('sub_category_id'));
        }

        $prods = $prods->get();

        $products = Helpers::product_data_formatting($prods, true);

        $response = ['status' => true, 'message' => 'Product Lists', 'data' => $products];

        return response()->json($response, 200);
    }

    public function stock_out_list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        $stock_limit = Helpers::get_business_settings('stock_limit');

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }
        $products = Product::where(['added_by' => 'seller', 'user_id' => $seller->id, 'product_type' => 'physical', 'request_status' => 1])
            ->where('current_stock', '<', $stock_limit)
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);
        /*$paginator->count();*/
        $products->map(function ($data) {
            $data = Helpers::product_data_formatting($data);
            return $data;
        });

        return response()->json([
            'total_size' => $products->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'products' => $products->items()
        ], 200);
    }

    public function upload_images(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'type' => 'required|in:product,thumbnail,meta',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $path = $request['type'] == 'product' ? '' : $request['type'] . '/';
        $image = ImageManager::upload('product/' . $path, 'png', $request->file('image'));

        return response()->json(['image_name' => $image, 'type' => $request['type']], 200);
    }

    // Digital product file upload
    public function upload_digital_product(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        try {
            $validator = Validator::make($request->all(), [
                'digital_file_ready' => 'required|mimes:jpg,jpeg,png,gif,zip,pdf',
            ]);

            if ($validator->errors()->count() > 0) {
                return response()->json(['errors' => Helpers::error_processor($validator)]);
            }

            $file = ImageManager::upload('product/digital-product/', $request->digital_file_ready->getClientOriginalExtension(), $request->file('digital_file_ready'));

            return response()->json(['digital_file_ready_name' => $file], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function add_new(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'category_id'           => 'required',
            'product_type'          => 'required',
            'digital_product_type'  => 'required_if:product_type,==,digital',
            'digital_file_ready'    => 'required_if:digital_product_type,==,ready_product',
            // 'unit'                  => 'required_if:product_type,==,physical',
            'images'                => 'required',
            'thumbnail'             => 'required',
            'discount_type'         => 'required|in:percent,flat',
            'tax'                   => 'required|min:0',
            'lang'                  => 'required',
            'unit_price'            => 'required|min:1',
            'purchase_price'        => 'required|min:1',
            'discount'              => 'required|gt:-1',
            'shipping_cost'         => 'required_if:product_type,==,physical|gt:-1',
            'code'                  => 'required|unique:products',
            'minimum_order_qty'     => 'required|numeric|min:1',
        ], [
            'name.required'                     => translate('Product name is required!'),
            'unit.required_if'                  => translate('Unit is required!'),
            'category_id.required'              => translate('category is required!'),
            'digital_file_ready.required_if'    => translate('Ready product upload is required!'),
            'digital_product_type.required_if'  => translate('Digital product type is required!'),
            'shipping_cost.required_if'         => translate('Shipping Cost is required!'),
            'images.required'                   => translate('Product images is required!'),
            'thumbnail.required'                    => translate('Product thumbnail is required!'),
            'code.required'                     => translate('Code is required!'),
            'minimum_order_qty.required'        => translate('The minimum order quantity is required!'),
            'minimum_order_qty.min'             => translate('The minimum order quantity must be positive!'),
        ]);

        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        // if ($brand_setting && empty($request->brand_id)) {
        //     $validator->after(function ($validator) {
        //         $validator->errors()->add(
        //             'brand_id', 'Brand is required!'
        //         );
        //     });
        // }

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['unit_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['unit_price'] <= $dis) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'unit_price',
                    translate('Discount can not be more or equal to the price!')
                );
            });
        }

        $product = new Product();
        $product->user_id = $seller->id;
        $product->added_by = "seller";

        $product->name = $request->name[array_search(Helpers::default_lang(), $request->lang)];
        $product->slug = Str::slug($request->name[array_search(Helpers::default_lang(), $request->lang)], '-') . '-' . Str::random(6);
        // $product->name = $request->name;
        // $product->slug = Str::slug($request->name, '-') . '-' . Str::random(6);

        $category = [];

        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->category_ids          = json_encode($category);
        $product->brand_id              = isset($request->brand_id) ? $request->brand_id : null;
        $product->unit                  = $request->product_type == 'physical' ? $request->unit : null;
        $product->net_weight            = $request->product_type == 'physical' ? $request->net_weight : null;
        $product->per_unit_price        = $request->product_type == 'physical' ? $request->per_unit_price : null;
        $product->package               = $request->product_type == 'physical' ? $request->package : null;
        $product->product_type          = $request->product_type;
        $product->digital_product_type  = $request->product_type == 'digital' ? $request->digital_product_type : null;
        $product->code                  = $request->code;
        $product->minimum_order_qty     = $request->minimum_order_qty;
        // $product->details               = $request->description;
        $product->details               = $request->description[array_search(Helpers::default_lang(), $request->lang)];

        $product->images                = json_encode($request->images);
        $product->thumbnail             = $request->thumbnail;
        $product->digital_file_ready    = $request->digital_file_ready;

        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $product->colors = $request->product_type == 'physical' ? json_encode($request->colors) : json_encode([]);
        } else {
            $colors = [];
            $product->colors = $request->product_type == 'physical' ? json_encode($colors) : json_encode([]);
        }

        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['name'] = 'choice_' . $no;
                $item['title'] = $request->choice[$key];
                $item['options'] = $request[$str];
                array_push($choice_options, $item);
            }
        }
        $product->choice_options = $request->product_type == 'physical' ? json_encode($choice_options) : json_encode([]);

        //combinations start
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                array_push($options, $request[$name]);
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        // dd($combinations);
        $variations = [];
        $stock_count = 0;
        if (!empty($combinations) && is_array($combinations[0]) && count($combinations[0]) > 0) {

            foreach ($combinations as $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name ?? '';
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = Convert::usd(abs($request['price_' . str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_' . str_replace('.', '_', $str)];
                $item['per_pack_quantity'] = abs($request['per_pack_quantity_' . str_replace('.', '_', $str)]);
                $item['qty'] = $request['qty_' . str_replace('.', '_', $str)];

                array_push($variations, $item);
                $stock_count += $item['qty'];
            }
        } else {
            $stock_count = (int)$request['current_stock'];
        }

        /*if ((integer)$request['current_stock'] != $stock_count) {
            $validator->after(function ($validator) {
                $validator->errors()->add('total_stock', 'Stock calculation mismatch!');
            });
        }*/

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        //combinations end
        $product->variation      = $request->product_type == 'physical' ? json_encode($variations) : json_encode([]);
        $product->unit_price = Convert::usd($request->unit_price);
        $product->purchase_price = Convert::usd($request->unit_price);
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount_type == 'flat' ? Convert::usd($request->discount) : $request->discount;
        $product->discount_type = $request->discount_type;
        $product->attributes     = $request->product_type == 'physical' ? json_encode($request->choice_attributes) : json_encode([]);
        $product->current_stock  = $request->product_type == 'physical' ? abs($stock_count) : 0;

        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_image = $request->meta_image;

        $product->video_provider = 'youtube';
        $product->video_url = $request->video_link;
        $product->request_status = Helpers::get_business_settings('new_product_approval') == 1 ? 0 : 1;
        $product->status = 0;
        $product->shipping_cost  = $request->product_type == 'physical' ? Convert::usd($request->shipping_cost) : 0;
        $product->multiply_qty = ($request->product_type == 'physical') ? ($request->multiplyQTY == 1 ? 1 : 0) : 0;
        $product->save();
        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != Helpers::default_lang()) {
                array_push($data, array(
                    'translationable_type' => 'App\Model\Product',
                    'translationable_id' => $product->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                ));
            }
            if ($request->description[$index] && $key != Helpers::default_lang()) {
                array_push($data, array(
                    'translationable_type' => 'App\Model\Product',
                    'translationable_id' => $product->id,
                    'locale' => $key,
                    'key' => 'description',
                    'value' => $request->description[$index],
                ));
            }
        }
        Translation::insert($data);

        return response()->json(['status' => true, 'message' => translate('successfully product added!')], 200);
    }

    public function edit(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => true,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $product = Product::withoutGlobalScopes()->with('translations')->find($id);
        $product = Helpers::set_data_format_vendor($product);

        if (!empty($product['bulk_pricing'])) {
            $product['bulk_pricing'] = json_decode($product['bulk_pricing'], true);
        }

        if (!empty($product['variation']) && is_array($product['variation'])) {
            $newVariations = [];

            foreach ($product['variation'] as $variation) {
                if (isset($variation['price'])) {
                    $variation['price'] = preg_replace('/[^\d.,]/', '', $variation['price']);
                }
                $newVariations[] = $variation;
            }

            $product['variation'] = $newVariations;
        }


        $response = ['status' => true, 'message' => 'Product details', 'product' => [$product]];

        return response()->json($response, 200);
    }

    public function updateStock(Request $request, $id)
    {

        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $product = Product::find($id);
        $product->current_stock = $request->input('quantity');
        $product->save();

        $response = ['status' => true, 'message' => 'Stock Updated success'];

        return response()->json($response, 200);
    }
    public function updatePrice(Request $request, $id)
    {

        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more')
            ]);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => translate('Invalid product')
            ]);
        }

        $old_selling_price = BackEndHelper::usd_to_currency($product->selling_price);
        $old_discount = $product->discount;
        $old_unit_price = BackEndHelper::usd_to_currency($product->unit_price);

        // mrp section
        if ($request['type'] == 'mrp') {
            if ($old_selling_price > $request['new_price'] || $request['new_price'] == 0) {

                return response()->json([
                    'status' => false,
                    'message' => translate('MRP can not be less than selling price!')
                ]);
            }

            $discount = (($request['new_price'] - $old_selling_price) / $request['new_price']) * 100;
            $discount = round($discount, 2);

            $product->unit_price = Convert::usd($request['new_price']);
            $product->discount = $discount;
            $product->discount_type = 'percent';
        }

        // selling_price
        if ($request['type'] == 'selling_price') {
            if ($old_unit_price < $request['selling_price'] || $request['selling_price'] == 0) {

                return response()->json([
                    'status' => false,
                    'message' => translate('Selling price can not be grater than selling_price!')
                ]);
            }

            $discount = (($old_unit_price - $request['selling_price']) / $old_unit_price) * 100;
            $discount = round($discount, 2);

            $product->selling_price = Convert::usd($request['selling_price']);
            $product->discount = $discount;
            $product->discount_type = 'percent';
        }

        // discount
        if ($request['type'] == 'discount') {
            if ($request['discount'] > 99) {

                return response()->json([
                    'status' => false,
                    'message' => translate('discount can not be grater than 99')
                ]);
            }
            $selling_price = ($old_unit_price / 100) * $request['discount'];

            $selling_price = $old_unit_price - round($selling_price, 2);

            $product->selling_price = Convert::usd($selling_price);
            $product->discount = $request['discount'];
            $product->discount_type = 'percent';
        }

        // quantity
        if ($request['type'] == 'quantity') {
            if ($request['quantity'] < 0) {

                return response()->json([
                    'status' => false,
                    'message' => translate('Quantity can not be 0')
                ]);
            }
            $product->current_stock = $request['quantity'];
        }

        // variation
        if ($request['type'] == 'variation') {
            $variations = json_decode($request['variation'], true);

            foreach ($variations as &$var) {
                if (isset($var['price'])) {
                    $cleanPrice = str_replace(',', '', trim($var['price']));
                    $var['price'] = Convert::usd($cleanPrice);
                }
            }

            $product->variation = $variations;
        }

        $product->save();

        $response = ['status' => true, 'message' => 'Updated successfully'];

        return response()->json($response, 200);
    }

    public function update(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $product = Product::find($id);

        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'category_id'           => 'required',
            'product_type'          => 'required',
            'digital_product_type'  => 'required_if:product_type,==,digital',
            'unit'                  => 'required_if:product_type,==,physical',
            'discount_type'         => 'required|in:percent,flat',
            'tax'                   => 'required|min:0',
            'lang'                  => 'required',
            'unit_price'            => 'required|min:1',
            'purchase_price'        => 'required|min:1',
            'discount'              => 'required|gt:-1',
            'shipping_cost'         => 'required_if:product_type,==,physical|gt:-1',
            'minimum_order_qty'     => 'required|numeric|min:1',
            'code'                  => 'required|numeric|min:1|digits_between:6,20|unique:products,code,' . $product->id,
        ], [
            'name.required'                     => 'Product name is required!',
            'category_id.required'              => 'category  is required!',
            'unit.required_if'                  => 'Unit is required!',
            'code.min'                          => 'The code must be positive!',
            'code.digits_between'               => 'The code must be minimum 6 digits!',
            'code.required'                     => 'Product code sku is required!',
            'minimum_order_qty.required'        => 'The minimum order quantity is required!',
            'minimum_order_qty.min'             => 'The minimum order quantity must be positive!',
            'digital_product_type.required_if'  => 'Digital product type is required!',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => [], 'errors' => Helpers::error_processor($validator)]);
        }

        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        if ($brand_setting && empty($request->brand_id)) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'brand_id',
                    'Brand is required!'
                );
            });
        }

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['unit_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['unit_price'] <= $dis) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'unit_price',
                    translate('Discount can not be more or equal to the price!')
                );
            });
        }


        $product->user_id = $seller->id;
        $product->added_by = "seller";

        $product->name = $request->name[array_search(Helpers::default_lang(), $request->lang)];
        $product->slug = Str::slug($request->name[array_search(Helpers::default_lang(), $request->lang)], '-') . '-' . Str::random(6);

        $category = [];

        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->category_ids          = json_encode($category);
        $product->brand_id              = isset($request->brand_id) ? $request->brand_id : null;
        $product->unit                  = $request->product_type == 'physical' ? $request->unit : null;
        $product->net_weight            = $request->product_type == 'physical' ? $request->net_weight : null;
        $product->per_unit_price        = $request->product_type == 'physical' ? $request->per_unit_price : null;
        $product->package               = $request->product_type == 'physical' ? $request->package : null;
        $product->product_type          = $request->product_type;
        $product->digital_product_type  = $request->product_type == 'digital' ? $request->digital_product_type : null;
        $product->code                  = $request->code;
        $product->minimum_order_qty     = $request->minimum_order_qty;
        $product->details               = $request->description[array_search(Helpers::default_lang(), $request->lang)];

        $product->images                = json_encode($request->images);
        $product->thumbnail             = $request->thumbnail;

        if ($request->product_type == 'digital') {
            if ($request->digital_product_type == 'ready_product' && $request->digital_file_ready) {
                if ($product->digital_file_ready) {
                    ImageManager::delete('product/digital-product/' . $product->digital_file_ready);
                }
                $product->digital_file_ready = $request->digital_file_ready;
            } elseif (($request->digital_product_type == 'ready_after_sell') && $product->digital_file_ready) {
                ImageManager::delete('product/digital-product/' . $product->digital_file_ready);
                $product->digital_file_ready = null;
            }
        } elseif ($request->product_type == 'physical' && $product->digital_file_ready) {
            ImageManager::delete('product/digital-product/' . $product->digital_file_ready);
            $product->digital_file_ready = null;
        }

        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $product->colors = $request->product_type == 'physical' ? json_encode($request->colors) : json_encode([]);
        } else {
            $colors = [];
            $product->colors = $request->product_type == 'physical' ? json_encode($colors) : json_encode([]);
        }

        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['name'] = 'choice_' . $no;
                $item['title'] = $request->choice[$key];
                $item['options'] = $request[$str];
                array_push($choice_options, $item);
            }
        }
        $product->choice_options = $request->product_type == 'physical' ? json_encode($choice_options) : json_encode([]);

        //combinations start
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                array_push($options, $request[$name]);
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        $variations = [];
        $stock_count = 0;
        if (!empty($combinations) && is_array($combinations[0]) && count($combinations[0]) > 0) {

            foreach ($combinations as $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name ?? '';
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = Convert::usd(abs($request['price_' . str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_' . str_replace('.', '_', $str)];
                $item['per_pack_quantity'] = abs($request['per_pack_quantity_' . str_replace('.', '_', $str)]);
                $item['qty'] = $request['qty_' . str_replace('.', '_', $str)];

                array_push($variations, $item);
                $stock_count += $item['qty'];
            }
        } else {
            $stock_count = (int)$request['current_stock'];
        }

        /*if ((integer)$request['current_stock'] != $stock_count) {
            $validator->after(function ($validator) {
                $validator->errors()->add('total_stock', 'Stock calculation mismatch!');
            });
        }*/

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        //combinations end
        $product->variation         = $request->product_type == 'physical' ? json_encode($variations) : json_encode([]);
        $product->unit_price        = Convert::usd($request->unit_price);
        $product->purchase_price    = Convert::usd($request->purchase_price);
        $product->tax               = $request->tax;
        $product->tax_type          = $request->tax_type;
        $product->discount          = $request->discount_type == 'flat' ? Convert::usd($request->discount) : $request->discount;
        $product->discount_type     = $request->discount_type;
        $product->attributes        = $request->product_type == 'physical' ? json_encode($request->choice_attributes) : json_encode([]);
        $product->current_stock     = $request->product_type == 'physical' ? $request->current_stock : 0;

        $product->meta_title        = $request->meta_title;
        $product->meta_description  = $request->meta_description;

        $product->shipping_cost     = $request->product_type == 'physical' ? (Helpers::get_business_settings('product_wise_shipping_cost_approval') == 1 ? $product->shipping_cost : Convert::usd($request->shipping_cost)) : 0;
        $product->multiply_qty      = ($request->product_type == 'physical') ? ($request->multiplyQTY == 1 ? 1 : 0) : 0;

        if (Helpers::get_business_settings('product_wise_shipping_cost_approval') == 1 && ($product->shipping_cost != Convert::usd($request->shipping_cost)) && ($request->product_type == 'physical')) {
            $product->temp_shipping_cost = Convert::usd($request->shipping_cost);
            $product->is_shipping_cost_updated = 0;
        }

        if ($request->has('meta_image')) {
            $product->meta_image = $request->meta_image;
        }

        $product->video_provider = 'youtube';
        $product->video_url = $request->video_link;

        if ($product->request_status == 2) {
            $product->request_status = 0;
        }
        $product->save();

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    [
                        'translationable_type' => 'App\Model\Product',
                        'translationable_id' => $product->id,
                        'locale' => $key,
                        'key' => 'name'
                    ],
                    ['value' => $request->name[$index]]
                );
            }
            if ($request->description[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    [
                        'translationable_type' => 'App\Model\Product',
                        'translationable_id' => $product->id,
                        'locale' => $key,
                        'key' => 'description'
                    ],
                    ['value' => $request->description[$index]]
                );
            }
        }

        return response()->json(['status' => true, 'message' => translate('successfully product updated!')], 200);
    }

    // public function status_update(Request $request)
    // {
    //     $data = Helpers::get_seller_by_token($request);
    //     if ($data['success'] == 1) {
    //         $seller = $data['data'];
    //     } else {
    //         return response()->json([
    //             'auth-001' => translate('Your existing session token does not authorize you any more')
    //         ], 401);
    //     }

    //     $product = Product::find($request->id);
    //     $product->status = $request->status;
    //     $product->save();

    //     return response()->json([
    //         'status' => true,
    //         'success' => translate('updated successfully'),
    //     ], 200);
    // }

    public function status_update(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        if ($request['status'] == 0) {

            Product::where([
                'id' => $request['id'],
                'added_by' => 'seller',
                'user_id' => $seller->id
            ])->update([
                'status' => $request['status'],
            ]);
        } elseif ($request['status'] == 1) {
            $product = Product::find($request['id']);
            if ($product && $product->request_status == 1) {

                $product->status = $request['status'];
                $product->save();
            } else {

                return response()->json([
                    'status' => false,
                    'success' => translate('Status update failed. Product must be approved'),
                ], 200);
            }
        } else {

            $product = Product::find($request['id']);
            if ($product) {
                $product->status = $request['status'];
                $product->save();
            }
        }

        return response()->json([
            'status' => true,
            'success' => translate('Updated successfully'),
        ], 200);
    }



    public function delete(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $product = Product::find($id);
        if ($product) {
            foreach (json_decode($product['images'], true) as $image) {
                ImageManager::delete('/product/' . $image);
            }
            ImageManager::delete('/product/thumbnail/' . $product['thumbnail']);
            $product->delete();
            FlashDealProduct::where(['product_id' => $id])->delete();
            DealOfTheDay::where(['product_id' => $id])->delete();
            return response()->json(['status' => true, 'message' => translate('successfully product deleted!')], 200);
        } else {
            return response()->json(['status' => true, 'message' => translate('product not available!')], 200);
        }
    }

    public function barcode_generate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'quantity' => 'required',
        ], [
            'id.required' => 'Product ID is required',
            'quantity.required' => 'Barcode quantity is required',
        ]);

        if ($request->limit > 270) {
            return response()->json(['code' => 403, 'message' => 'You can not generate more than 270 barcode']);
        }
        $product = Product::where('id', $request->id)->first();
        $quantity = $request->quantity ?? 30;
        if (isset($product->code)) {
            $pdf = app()->make(PDF::class);
            $pdf->loadView('seller-views.product.barcode-pdf', compact('product', 'quantity'));
            $pdf->save(storage_path('app/public/product/barcode.pdf'));
            return response()->json(asset('storage/app/public/product/barcode.pdf'));
        } else {
            return response()->json(['message' => translate('Please update product code!')], 203);
        }
    }

    public function product_management(Request $request)
    {

        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $dt['total_product'] = Product::where(['added_by' => 'seller', 'user_id' => $seller['id']])->count();
        $dt['active_product'] = Product::where(['added_by' => 'seller', 'user_id' => $seller['id'], 'status' => 1])->count();
        $dt['inactive_product'] = Product::where(['added_by' => 'seller', 'user_id' => $seller['id'], 'status' => 0])->count();

        $category_ids = Product::where(['added_by' => 'seller', 'user_id' => $seller['id']])->pluck('category_id');

        $categories = Category::whereIn('id', $category_ids)->get();

        foreach ($categories as $key => $category) {
            $category->icon = $category->icon == 'def.png' ? asset('storage/app/' . $category->icon) : asset('storage/app/public/category/' . $category->icon);
            if ($category->childes->isNotEmpty()) {
                foreach ($category->childes as $key => $cat) {
                    $cat->icon = $cat->icon == 'def.png' ? asset('storage/app/' . $category->icon) : asset('storage/app/public/category/' . $cat->icon);
                    if ($cat->childes->isNotEmpty()) {
                        foreach ($cat->childes as $key => $dt_cat) {
                            $dt_cat->icon = $dt_cat->icon  == 'def.png' ? asset('storage/app/' . $category->icon) : asset('storage/app/public/category/' . $dt_cat->icon);
                        }
                    }
                }
            }
        }
        $dt['categories'] = $categories;

        $response = ['status' => true, 'message' => 'Product Management', 'data' => $dt, 'seller_id' => $seller['id']];
        return response()->json($response);
    }
}
