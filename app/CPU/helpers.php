<?php

namespace App\CPU;

use App\Model\Admin;
use App\Model\BusinessSetting;
use App\Model\Category;
use App\Model\Color;
use App\Model\Coupon;
use App\Model\Currency;
use App\Model\Order;
use App\Model\OrderStatusHistory;
use App\Model\Review;
use App\Model\Seller;
use App\Model\ShippingMethod;
use App\Model\Shop;
use App\Model\Wishlist;
use App\Model\Cart;
use App\Model\ShopFollower;
use App\Model\Country;
use App\Model\Product;
use App\Model\UserNotification;
use App\Model\ApplicationDocument;
use App\Model\Room;
use App\Model\RoomUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class Helpers
{
    public static function status($id)
    {
        if ($id == 1) {
            $x = 'active';
        } elseif ($id == 0) {
            $x = 'in-active';
        }

        return $x;
    }

    public static function getAssignedUserIds($application, $type = 'auditor') {
        $userIds = [];
        if($type == 'auditor') {
            $userIds = explode(',', $application->auditor_team_ids);
            if($application->auditor_id) {
                $userIds[] = $application->auditor_id;
            }
        }
        if($type == 'quality') {
            $userIds = explode(',', $application->quality_team_ids);
            if($application->quality_id) {
                $userIds[] = $application->quality_id;
            }
        }
        if($type=='accreditation') {
            $userIds = explode(',', $application->accreditation_team_ids);
            if($application->accreditation_id) {
                $userIds[] = $application->accreditation_id;
            }
        }

        if($type=='office_assessment') {
            $userIds = explode(',', $application->office_assessment_team_ids);
            if($application->office_assessment_id) {
                $userIds[] = $application->office_assessment_id;
            }
        }

        if($type=='witness_assessment') {
            $userIds = explode(',', $application->witness_assessment_team_ids);
            if($application->witness_assessment_id) {
                $userIds[] = $application->witness_assessment_id;
            }
        }
        return $userIds;
    }

    public static function createRoom($application_id, $type = 'auditor') {
        $types = ['auditor' => 'Assessor/Auditor', 'quality' => 'Quality/Technical', 'accreditation' => 'Accreditation Committee', 'office_assessment' => 'Office Assessment', 'witness_assessment' => 'Witness Assessment'];
        $room = new Room;
        $room->title = $types[$type];
        $room->description = $types[$type];
        $room->room_type = $type;
        $room->application_id = $application_id;
        $room->save();

        return $room;
    }

    public static function createRoomUsers($roomId, $userIds = []) {
        $postData = [];

        if(!empty($userIds)) {
            foreach($userIds as $userId) {
                if(RoomUser::where([
                    'room_id' => $roomId,
                    'user_id' => $userId
                ])->first() == null) {
                    $postData[] = [
                        'room_id' => $roomId,
                        'user_id' => $userId
                    ];
                }
            }
            if(!empty($postData)) {
                RoomUser::insert($postData);
            }
            return true;
        }
    }

    public static function updateTeamLeadNdMemberName($application, $schemeId, $teamLeaderName, $reportingTeamName) {
        if(is_array($application->general_info)) {
            $generalInfo = ($application->general_info);
        } else {
            $generalInfo = json_decode($application->general_info, true);
        }
        switch ($schemeId) {
            case '1':
            case '2':
            case '3':
            case '6':
            case '7':
            case '8':
            case '9':
                $generalInfo['team_leader_name'] = $teamLeaderName;
                $generalInfo['reporting_assessor'] = $reportingTeamName;
                $application->general_info = $generalInfo;
                break;
            case '4':
                $generalInfo['name'] = $teamLeaderName;
                $application->general_info = $generalInfo;
                break;
            case '5':
                $teamInfos = $generalInfo['details_of_the_assessment_team'];
                foreach($teamInfos as $ind => $arr) {
                    if($arr['role'] == 'L A') {
                        $teamInfos[$ind]['name'] = $teamLeaderName;
                    }
                    if($arr['role'] == 'T A') {
                        $teamInfos[$ind]['name'] = $reportingTeamName;
                    }
                }
                $generalInfo['details_of_the_assessment_team'] = $teamInfos;
                $application->general_info = $generalInfo;
                break;
            case '12':
                $teamInfos = $generalInfo['details_of_the_assessment_team'];
                foreach($teamInfos as $ind => $arr) {
                    if($arr['role'] == 'TL') {
                        $teamInfos[$ind]['name'] = $teamLeaderName;
                    }
                    if($arr['role'] == 'TA') {
                        $teamInfos[$ind]['name'] = $reportingTeamName;
                    }
                }
                $generalInfo['details_of_the_assessment_team'] = $teamInfos;
                $application->general_info = $generalInfo;
                break;
            default:
                break;
        }
        $application->save();

        return false;
    }
    public static function updateApplicationAssesTeamLeadNdMemberName($application, $schemeId, $teamLeaderName, $reportingTeamName) {
        if(is_array($application->main_general_info)) {
            $generalInfo = $application->main_general_info;
        } else {
            $generalInfo = json_decode($application->main_general_info, true);
        }
        $technicalAssessment = ($application->technical_assessment);
        $verticalAssessment = ($application->vertical_assessment);
        switch ($schemeId) {
            case '1':
            case '2':
            case '3':
            case '6':
            case '7':
            case '8':
            case '9':
                $generalInfo['team_leader_name'] = $teamLeaderName;
                $generalInfo['reporting_assessor'] = $reportingTeamName;
                $application->main_general_info = $generalInfo;
                
                $technicalAssessment['team_leader_name'] = $teamLeaderName;
                $technicalAssessment['reporting_assessor'] = $reportingTeamName;
                $application->technical_assessment = $technicalAssessment;
                
                $verticalAssessment['team_leader_name'] = $teamLeaderName;
                $verticalAssessment['reporting_assessor'] = $reportingTeamName;
                $application->vertical_assessment = $verticalAssessment;
                break;
            case '4':
                $generalInfo['name'] = $teamLeaderName;
                $application->main_general_info = $generalInfo;
                break;
            case '5':
                $teamInfos = $generalInfo['details_of_the_assessment_team'];
                foreach($teamInfos as $ind => $arr) {
                    if($arr['role'] == 'L A') {
                        $teamInfos[$ind]['name'] = $teamLeaderName;
                    }
                    if($arr['role'] == 'T A') {
                        $teamInfos[$ind]['name'] = $reportingTeamName;
                    }
                }
                $generalInfo['details_of_the_assessment_team'] = $teamInfos;
                $application->main_general_info = $generalInfo;
                break;
            case '12':
                $teamInfos = $generalInfo['details_of_the_assessment_team'];
                foreach($teamInfos as $ind => $arr) {
                    if($arr['role'] == 'TL') {
                        $teamInfos[$ind]['name'] = $teamLeaderName;
                    }
                    if($arr['role'] == 'TA') {
                        $teamInfos[$ind]['name'] = $reportingTeamName;
                    }
                }
                $generalInfo['details_of_the_assessment_team'] = $teamInfos;
                $application->main_general_info = $generalInfo;
                break;
            default:
                break;
        }
        $application->save();

        return false;
    }

    public static function convertNumberToWords($number)
    {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;

        $units = [
            '',
            'One',
            'Two',
            'Three',
            'Four',
            'Five',
            'Six',
            'Seven',
            'Eight',
            'Nine',
            'Ten',
            'Eleven',
            'Twelve',
            'Thirteen',
            'Fourteen',
            'Fifteen',
            'Sixteen',
            'Seventeen',
            'Eighteen',
            'Nineteen'
        ];

        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $thousands = ['', 'Thousand', 'Million', 'Billion'];

        $words = '';

        if ($no == 0) {
            $words = 'Zero';
        } else {
            $place = 0;
            while ($no > 0) {
                if ($no % 1000 != 0) {
                    $words = self::convertHundreds($no % 1000) . ' ' . $thousands[$place] . ' ' . $words;
                }
                $no = floor($no / 1000);
                $place++;
            }
        }


        if ($point > 0) {
            $words .= ' and ' . self::convertHundreds($point) . ' Paise';
        }

        return $words . ' Rupees Only';
    }

    public static function convertHundreds($number)
    {
        $units = [
            '',
            'One',
            'Two',
            'Three',
            'Four',
            'Five',
            'Six',
            'Seven',
            'Eight',
            'Nine',
            'Ten',
            'Eleven',
            'Twelve',
            'Thirteen',
            'Fourteen',
            'Fifteen',
            'Sixteen',
            'Seventeen',
            'Eighteen',
            'Nineteen'
        ];

        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        if ($number < 20) {
            return $units[$number];
        } elseif ($number < 100) {
            return $tens[floor($number / 10)] . ' ' . $units[$number % 10];
        } else {
            return $units[floor($number / 100)] . ' Hundred ' . self::convertHundreds($number % 100);
        }
    }

    public static function transaction_formatter($transaction)
    {
        if ($transaction['paid_by'] == 'customer') {
            $user = User::find($transaction['payer_id']);
            $payer = $user->f_name . ' ' . $user->l_name;
        } elseif ($transaction['paid_by'] == 'seller') {
            $user = Seller::find($transaction['payer_id']);
            $payer = $user->f_name . ' ' . $user->l_name;
        } elseif ($transaction['paid_by'] == 'admin') {
            $user = Admin::find($transaction['payer_id']);
            $payer = $user->name;
        }

        if ($transaction['paid_to'] == 'customer') {
            $user = User::find($transaction['payment_receiver_id']);
            $receiver = $user->f_name . ' ' . $user->l_name;
        } elseif ($transaction['paid_to'] == 'seller') {
            $user = Seller::find($transaction['payment_receiver_id']);
            $receiver = $user->f_name . ' ' . $user->l_name;
        } elseif ($transaction['paid_to'] == 'admin') {
            $user = Admin::find($transaction['payment_receiver_id']);
            $receiver = $user->name;
        }

        $transaction['payer_info'] = $payer;
        $transaction['receiver_info'] = $receiver;

        return $transaction;
    }

    public static function get_customer($request = null)
    {
        $user = null;
        if (auth('customer')->check()) {
            $user = auth('customer')->user(); // for web
        } elseif ($request != null && $request->user() != null) {
            $user = $request->user(); //for api
        } elseif (session()->has('customer_id')) {
            $user = User::find(session('customer_id'));
        }

        if ($user == null) {
            $user = 'offline';
        }

        return $user;
    }

    public static function coupon_discount($request)
    {
        $discount = 0;
        $user = Helpers::get_customer($request);
        $couponLimit = Order::where('customer_id', $user->id)
            ->where('coupon_code', $request['coupon_code'])->count();

        $coupon = Coupon::where(['code' => $request['coupon_code']])
            ->where('limit', '>', $couponLimit)
            ->where('status', '=', 1)
            ->whereDate('start_date', '<=', Carbon::parse()->toDateString())
            ->whereDate('expire_date', '>=', Carbon::parse()->toDateString())->first();

        if (isset($coupon)) {
            $total = 0;
            foreach (CartManager::get_cart(CartManager::get_cart_group_ids($request)) as $cart) {
                $product_subtotal = $cart['price'] * $cart['quantity'];
                $total += $product_subtotal;
            }
            if ($total >= $coupon['min_purchase']) {
                if ($coupon['discount_type'] == 'percentage') {
                    $discount = (($total / 100) * $coupon['discount']) > $coupon['max_discount'] ? $coupon['max_discount'] : (($total / 100) * $coupon['discount']);
                } else {
                    $discount = $coupon['discount'];
                }
            }
        }

        return $discount;
    }

    public static function default_lang()
    {
        if (strpos(url()->current(), '/api')) {
            $lang = App::getLocale();
        } elseif (session()->has('local')) {
            $lang = session('local');
        } else {
            $data = Helpers::get_business_settings('language');
            $code = 'en';
            $direction = 'ltr';
            foreach ($data as $ln) {
                if (array_key_exists('default', $ln) && $ln['default']) {
                    $code = $ln['code'];
                    if (array_key_exists('direction', $ln)) {
                        $direction = $ln['direction'];
                    }
                }
            }
            session()->put('local', $code);
            Session::put('direction', $direction);
            $lang = $code;
        }
        return $lang;
    }

    public static function rating_count($product_id, $rating)
    {
        return Review::where(['product_id' => $product_id, 'rating' => $rating])->whereNull('delivery_man_id')->count();
    }

    public static function get_business_settings($name)
    {
        $config = null;
        $check = ['currency_model', 'currency_symbol_position', 'system_default_currency', 'language', 'company_name', 'decimal_point_settings', 'product_brand', 'digital_product', 'company_email', 'recaptcha'];

        if (in_array($name, $check) == true && session()->has($name)) {
            $config = session($name);
        } else {
            $data = BusinessSetting::where(['type' => $name])->first();
            if (isset($data)) {
                $config = json_decode($data['value'], true);
                if (is_null($config)) {
                    $config = $data['value'];
                }
            }

            if (in_array($name, $check) == true) {
                session()->put($name, $config);
            }
        }

        if ($name === 'social_login' && !is_array($config)) {
            return [];
        }

        return $config;
    }

    public static function get_settings($object, $type)
    {
        $config = null;
        foreach ($object as $setting) {
            if ($setting['type'] == $type) {
                $config = $setting;
            }
        }
        return $config;
    }

    public static function get_shipping_methods($seller_id, $type)
    {
        if ($type == 'admin') {
            return ShippingMethod::where(['status' => 1])->where(['creator_type' => 'admin'])->get();
        } else {
            return ShippingMethod::where(['status' => 1])->where(['creator_id' => $seller_id, 'creator_type' => $type])->get();
        }
    }

    public static function get_shipping_method_name($id)
    {
        return ShippingMethod::where(['id' => $id])->first()->title ?? "";
    }

    public static function get_image_path($type)
    {
        $path = asset('storage/app/public/brand');
        return $path;
    }
    public static function getApplicationDocument($application_id)
    {
        $document = ApplicationDocument::where('application_id',$application_id)->first();
        if($document){
            return $document;
        } 
        return false;
    }



    public static function set_data_format($data, $user_id = "")
    {
        $colors = is_array($data['colors']) ? $data['colors'] : json_decode($data['colors']);
        $query_data = Color::whereIn('code', $colors)->pluck('name', 'code')->toArray();
        $color_final = [];
        foreach ($query_data as $key => $color) {
            $color_final[] = array(
                'name' => $color ?? "",
                'code' => $key,
            );
        }

        if (isset($data['added_by']) && $data['added_by'] == "seller") {
            if (isset($data->seller)) {
                $data['shop'] = Helpers::set_shop_data($data->seller->shop ?? []);
            } else {

                $product = Product::find($data['id']);
                $data['shop'] = Helpers::set_shop_data($product->seller->shop ?? []);
            }
        } else {
            $data['shop'] = Helpers::set_shop_data($data->seller->shop ?? []);
        }

        $variation = [];

        $images = is_array($data['images']) ? $data['images'] : json_decode($data['images']);
        $color_images = isset($data['color_image']) ? (is_array($data['color_image']) ? $data['color_image'] : json_decode($data['color_image'])) : [];

        if (!empty($images)) {
            foreach ($images as $ikey => $image) {
                $images[$ikey] = $image == 'def.png' ? asset('storage/app/' . $image) : asset('storage/app/public/product/' . $image);
            }
        }

        if (!empty($color_images)) {
            foreach ($color_images as $ckey => $cimage) {
                $color_images[$ckey]->color = $cimage->color ?? "FFFFFF";
                $color_images[$ckey]->image_name = $cimage->image_name == 'def.png' ? asset('storage/app/' . $cimage->image_name) : asset('storage/app/public/product/' . $cimage->image_name);
            }
        }

        $data['thumbnail'] = $data['thumbnail'] == 'def.png' ? asset('storage/app/' . $data['thumbnail']) : asset('storage/app/public/product/thumbnail/' . $data['thumbnail']);

        $data['category_ids'] = is_array($data['category_ids']) ? $data['category_ids'] : json_decode($data['category_ids']);
        $data['images'] = $images;
        $data['color_image'] = $color_images;
        $data['colors_formatted'] = $color_final;
        $attributes = [];
        if ((is_array($data['attributes']) ? $data['attributes'] : json_decode($data['attributes'])) != null) {
            $attributes_arr = is_array($data['attributes']) ? $data['attributes'] : json_decode($data['attributes']);
            foreach ($attributes_arr as $attribute) {
                $attributes[] = (int)$attribute;
            }
        }
        $data['attributes'] = $attributes;
        $data['choice_options'] = is_array($data['choice_options']) ? $data['choice_options'] : json_decode(trim($data['choice_options']));
        $variation_arr = is_array($data['variation']) ? $data['variation'] : json_decode($data['variation'], true);
        if (!empty($variation_arr)) {
            foreach ($variation_arr as $var) {
                $variation[] = [
                    'type' => isset($var['type']) ? $var['type'] : '',
                    'price' => isset($var['price']) ? Helpers::currency_converter((float)$var['price']) : 0,
                    'sku' => $var['sku'] ?? '',
                    'per_pack_quantity' => isset($var['per_pack_quantity']) ? (int)$var['per_pack_quantity'] : 1,
                    'qty' => isset($var['qty']) ? (int)$var['qty'] : 1,
                ];
            }
        }
        $data['variation'] = $variation ?? [];

        /**
         * Product Model For API
         */
        $product = [];
        $product['id'] = $data['id'] ?? "";
        $product['category_ids'] = is_array($data['category_ids']) ? $data['category_ids'] : json_decode($data['category_ids']);
        $product['user_id'] = $data['user_id'] ?? "";
        $product['shop'] = $data['shop'];
        $product['name'] = $data['name'] ?? "";
        $product['details'] = $data['details'] ?? "";

        $product['details'] = $data['details'] ?? "";
        $product['details'] = $data['details'] ?? "";
        $product['details'] = $data['details'] ?? "";
        $product['details'] = $data['details'] ?? "";
        $product['details'] = $data['details'] ?? "";
        $product['details'] = $data['details'] ?? "";
        $product['details'] = $data['details'] ?? "";

        $product['free_delivery'] = strval($data['free_delivery'] ?? 0);
        $product['returnable'] = strval($data['returnable'] ?? 0);
        $product['is_warranty_policy'] = strval($data['is_warranty_policy'] ?? 0);
        $product['top_brand'] = strval($data['top_brand'] ?? 0);
        $product['is_verified'] = strval($data['is_verified'] ?? 0);
        $product['made_in'] = strval($data['made_in'] ?? 'India');
        $product['warranty'] = strval($data['warranty'] ?? 0);
        $product['manufacturing_date'] = strval($data['manufacturing_date'] ?? '');

        $product['slug'] = $data['slug'] ?? "";
        $product['images'] = $data['images'] ?? "";
        $product['color_image'] = $data['color_image'] ?? [];
        $product['thumbnail'] = $data['thumbnail'] ?? "";
        $product['brand_id'] = $data['brand_id'] ?? 0;
        $product['unit'] = $data['unit'] ?? "";
        $product['min_qty'] = $data['min_qty'] ?? "";
        $product['featured'] = $data['featured'] ?? 0;
        $product['refundable'] = $data['refundable'] ?? "";
        $product['variant_product'] = $data['variant_product'] ?? "";
        $product['attributes'] = $data['attributes'] ?? "";
        $product['choice_options'] = $data['choice_options'] ?? "";
        $product['variation'] = $data['variation'] ?? "";
        $product['refundable'] = $data['refundable'] ?? "";
        $product['weight'] = "1 " . $data['unit'];

        $product['published'] = $data['published'] ?? "";
        // dd($data);
        $product['unit_price'] = Helpers::currency_converter($data['unit_price']);
        $product['selling_price'] = Helpers::currency_converter($data['selling_price'] ?? 0);


        $sp = $data['unit_price'];
        $tax_amount = 0;
        if ($data['tax_model'] == 'exclude' && $data['tax_type'] == 'percent') {
            $tax_amount = ($sp * $data['tax']) / 100;
        }
        $sp += $tax_amount;
        if ($data['discount'] > 0 && $data['discount_type'] == 'percent') {
            $sp = $sp - ($sp * $data['discount']) / 100;
        } else if ($data['discount'] > 0 && $data['discount_type'] == 'flat') {
            $sp = $sp - $data['discount'];
        }

        $discount_string = "";
        if ($data['discount'] > 0 && $data['discount_type'] == 'percent') {
            $discount_string = $data['discount'] . "% OFF";
        } else if ($data['discount'] > 0 && $data['discount_type'] == 'flat') {
            $discount_string = Helpers::currency_converter($data['discount']) . " OFF";
        }

        $product['special_price'] = Helpers::currency_converter($sp);

        $product['purchase_price'] = Helpers::currency_converter($data['purchase_price']);
        $product['tax'] = $data['tax'] ?? "";
        $product['tax_type'] = $data['tax_type'] ?? "";
        $product['tax_model'] = $data['tax_model'] ?? "";


        $product['tax_amount'] = Helpers::currency_converter($tax_amount) ?? "0.00";
        $product['discount'] = $data['discount'] ? (int)$data['discount'] : 0;
        $product['discount_type'] = $data['discount_type'] ?? "";
        $product['discount_string'] = $discount_string;

        $product['current_stock'] = $data['current_stock'] ?? 1;
        $product['per_pack_quantity'] = $data['per_pack_quantity'] ?? "";
        $product['minimum_order_qty'] = $data['minimum_order_qty'] ?? "";
        $product['free_shipping'] = $data['free_shipping'] ?? "";
        $product['created_at'] = $data['created_at'] ?? "";
        $product['updated_at'] = $data['updated_at'] ?? "";
        $product['status'] = $data['status'] ?? "";
        $product['featured_status'] = $data['featured_status'] ?? "";
        $product['meta_title'] = $data['meta_title'] ?? "";
        $product['meta_description'] = $data['meta_description'] ?? "";
        $product['meta_image'] = $data['meta_image'] ?? "";
        $product['request_status'] = $data['request_status'] ?? 0;
        $product['shipping_cost'] = Helpers::currency_converter($data['shipping_cost']);
        $product['multiply_qty'] = $data['multiply_qty'] ?? 0;
        $product['code'] = $data['code'] ?? "";
        $product['reviews_count'] = $data['reviews_count'] ?? 0;
        $product['rating'] = $data['rating'] ?? [];
        $product['tags'] = $data['tags'] ?? [];
        $product['translations'] = $data['translations'] ?? [];
        $product['share_link'] = $data['share_link'] ?? "";
        $product['reviews'] = Helpers::set_review_data($data['reviews']);
        $product['colors_formatted'] = $data['colors_formatted'] ?? [];
        $product['is_favorite'] = Wishlist::where(['product_id' => $data['id'], 'customer_id' => $user_id])->count() > 0 ? true : false;
        $product['is_cart'] = Cart::where(['product_id' => $data['id'], 'customer_id' => $user_id])->count() > 0 ? true : false;
        $product['cart_id'] = Cart::where(['product_id' => $data['id'], 'customer_id' => $user_id])->first()->id ?? 0;

        $product['manufacturing_date'] = '2023-12-15';
        $product['refundable']  = '1';
        $product['made_in'] = 'India';
        $product['warranty'] = '1 Year';
        $product['use_coins_with_amount'] = '10';
        $product['amount_after_coin_use'] = '10';
        // $user_id;

        return $product;
    }

    public static function set_data_format_vendor($data, $user_id = "")
    {
        $colors = is_array($data['colors']) ? $data['colors'] : json_decode($data['colors']);
        $query_data = Color::whereIn('code', $colors)->pluck('name', 'code')->toArray();
        $color_final = [];
        foreach ($query_data as $key => $color) {
            $color_final[] = array(
                'name' => $color ?? "",
                'code' => $key,
            );
        }

        if (isset($data['added_by']) && $data['added_by'] == "seller") {
            $data['shop'] = Helpers::set_shop_data($data->seller->shop ?? []);
        } else {
            $data['shop'] = Helpers::set_shop_data($data->seller->shop ?? []);
        }

        $variation = [];

        $images = is_array($data['images']) ? $data['images'] : json_decode($data['images']);
        $color_images = isset($data['color_image']) ? (is_array($data['color_image']) ? $data['color_image'] : json_decode($data['color_image'])) : [];

        if (!empty($images)) {
            foreach ($images as $ikey => $image) {
                $images[$ikey] = $image == 'def.png' ? asset('storage/app/' . $image) : asset('storage/app/public/product/' . $image);
            }
        }

        if (!empty($color_images)) {
            foreach ($color_images as $ckey => $cimage) {
                $color_images[$ckey]->color = $cimage->color ?? "FFFFFF";
                $color_images[$ckey]->image_name = $cimage->image_name == 'def.png' ? asset('storage/app/' . $cimage->image_name) : asset('storage/app/public/product/' . $cimage->image_name);
            }
        }

        $data['thumbnail'] = $data['thumbnail'] == 'def.png' ? asset('storage/app/' . $data['thumbnail']) : asset('storage/app/public/product/thumbnail/' . $data['thumbnail']);

        $data['category_ids'] = is_array($data['category_ids']) ? $data['category_ids'] : json_decode($data['category_ids']);
        $data['images'] = $images;
        $data['color_image'] = $color_images;
        $data['colors_formatted'] = $color_final;
        $attributes = [];
        if ((is_array($data['attributes']) ? $data['attributes'] : json_decode($data['attributes'])) != null) {
            $attributes_arr = is_array($data['attributes']) ? $data['attributes'] : json_decode($data['attributes']);
            foreach ($attributes_arr as $attribute) {
                $attributes[] = (int)$attribute;
            }
        }
        $data['attributes'] = $attributes;
        $data['choice_options'] = is_array($data['choice_options']) ? $data['choice_options'] : json_decode(trim($data['choice_options']));
        $variation_arr = is_array($data['variation']) ? $data['variation'] : json_decode($data['variation'], true);
        if (!empty($variation_arr)) {
            foreach ($variation_arr as $var) {
                $variation[] = [
                    'type' => isset($var['type']) ? $var['type'] : '',
                    'price' => isset($var['price']) ? Helpers::currency_converter((float)$var['price']) : 0,
                    'sku' => $var['sku'] ?? '',
                    'per_pack_quantity' => isset($var['per_pack_quantity']) ? (int)$var['per_pack_quantity'] : 1,
                    'qty' => isset($var['qty']) ? (int)$var['qty'] : 1,
                ];
            }
        }
        $data['variation'] = $variation ?? [];

        /**
         * Product Model For API
         */
        // $product = [];
        $data['id'] = $data['id'] ?? "";
        $data['category_ids'] = is_array($data['category_ids']) ? $data['category_ids'] : json_decode($data['category_ids']);
        $data['user_id'] = $data['user_id'] ?? "";
        $data['shop'] = $data['shop'];
        $data['name'] = $data['name'] ?? "";
        $data['slug'] = $data['slug'] ?? "";
        $data['images'] = $data['images'] ?? "";
        $data['color_image'] = $data['color_image'] ?? [];
        $data['thumbnail'] = $data['thumbnail'] ?? "";
        $data['brand_id'] = $data['brand_id'] ?? 0;
        $data['unit'] = $data['unit'] ?? "";
        $data['min_qty'] = $data['min_qty'] ?? "";
        $data['featured'] = $data['featured'] ?? 0;
        $data['refundable'] = $data['refundable'] ?? "";
        $data['variant_product'] = $data['variant_product'] ?? "";
        $data['attributes'] = $data['attributes'] ?? "";
        $data['choice_options'] = $data['choice_options'] ?? "";
        $data['variation'] = $data['variation'] ?? "";
        $data['refundable'] = $data['refundable'] ?? "";
        $data['weight'] = "1 " . $data['unit'];

        $data['published'] = $data['published'] ?? "";

        $sp = $data['unit_price'];
        $tax_amount = 0;
        if ($data['tax_model'] == 'exclude' && $data['tax_type'] == 'percent') {
            $tax_amount = ($sp * $data['tax']) / 100;
        }
        $sp += $tax_amount;
        if ($data['discount'] > 0 && $data['discount_type'] == 'percent') {
            $sp = $sp - ($sp * $data['discount']) / 100;
        } else if ($data['discount'] > 0 && $data['discount_type'] == 'flat') {
            $sp = $sp - $data['discount'];
            $data['discount'] = BackEndHelper::usd_to_currency($data['discount']);
        }
        $data['special_price'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($sp));
        $data['unit_price'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($data['unit_price']));
        $data['selling_price'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($data['selling_price']));

        $data['purchase_price'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($data['purchase_price']));
        $data['tax'] = $data['tax'] ?? "";
        $data['tax_type'] = $data['tax_type'] ?? "";
        $data['tax_model'] = $data['tax_model'] ?? "";
        $data['tax_amount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($tax_amount)) ?? "0.00";
        $data['discount'] = $data['discount'] ? (int)$data['discount'] : 0;
        $data['discount_type'] = $data['discount_type'] ?? "";
        $data['current_stock'] = $data['current_stock'] ?? 1;
        $data['minimum_order_qty'] = $data['minimum_order_qty'] ?? "";
        $data['free_shipping'] = $data['free_shipping'] ?? "";
        $data['created_at'] = $data['created_at'] ?? "";
        $data['updated_at'] = $data['updated_at'] ?? "";
        $data['status'] = $data['status'] ?? "";
        $data['featured_status'] = $data['featured_status'] ?? "";
        $data['meta_title'] = $data['meta_title'] ?? "";
        $data['meta_description'] = $data['meta_description'] ?? "";
        $data['meta_image'] = $data['meta_image'] ?? "";
        $data['request_status'] = $data['request_status'] ?? 0;
        $data['shipping_cost'] = strval(BackEndHelper::usd_to_currency($data['shipping_cost']));
        $data['multiply_qty'] = $data['multiply_qty'] ?? 0;
        $data['code'] = $data['code'] ?? "";
        $data['reviews_count'] = $data['reviews_count'] ?? 0;
        $data['rating'] = $data['rating'] ?? [];
        $data['tags'] = $data['tags'] ?? [];
        $data['translations'] = $data['translations'] ?? [];
        $data['share_link'] = $data['share_link'] ?? "";
        $data['reviews'] = Helpers::set_review_data($data['reviews']);
        $data['colors_formatted'] = $data['colors_formatted'] ?? [];
        $data['is_favorite'] = Wishlist::where(['product_id' => $data['id'], 'customer_id' => $user_id])->count() > 0 ? true : false;
        $data['is_cart'] = Cart::where(['product_id' => $data['id'], 'customer_id' => $user_id])->count() > 0 ? true : false;
        $data['cart_id'] = Cart::where(['product_id' => $data['id'], 'customer_id' => $user_id])->first()->id ?? 0;
        $data['colors'] = $colors;
        $data['manufacturing_date'] = '2023-12-15';
        $data['refundable']  = '1';
        $data['made_in'] = 'India';
        $data['short_description'] = $data['short_description'] ?? "";
        $data['specification'] = $data['specification'] ?? "";
        $data['warranty'] = $data['warranty'] ?? '1 Year';
        $data['use_coins_with_amount'] = '10';
        $data['amount_after_coin_use'] = '10';
        $data['denied_note'] = "";
        $data['temp_shipping_cost'] = "";
        $data['is_shipping_cost_updated'] = "";

        $data['category_id'] = $data['category_id'] ?? "";
        $data['sub_category_id'] = $data['sub_category_id'] ?? "";
        $data['sub_sub_category_id'] = $data['sub_sub_category_id'] ?? "";
        $data['digital_product_type'] = $data['digital_product_type'] ?? "";
        $data['digital_file_ready'] = $data['digital_file_ready'] ?? "";
        $data['flash_deal'] = $data['flash_deal'] ?? "";
        $data['video_url'] = $data['video_url'] ?? "";
        $data['attachment'] = $data['attachment'] ?? "";

        // $user_id;
        unset($data['seller']);
        return $data;
    }

    public static function product_data_formatting($data, $multi_data = false, $user_id = "")
    {
        if ($data) {
            $storage = [];
            if ($multi_data == true) {
                foreach ($data as $item) {
                    $storage[] = Helpers::set_data_format($item, $user_id);
                }
                $data = $storage;
            } else {
                $data = Helpers::set_data_format($data, $user_id);
            }

            return $data;
        }
        return null;
    }

    public static function set_order_products($data, $multi_data = false, $user_id = "")
    {
        if ($data) {
            $storage = [];
            if ($multi_data == true) {
                foreach ($data as $item) {
                    $storage[] = Helpers::set_order_data_format($item, $user_id);
                }
                $data = $storage;
            } else {
                $data = Helpers::set_order_data_format($data, $user_id);
            }

            return $data;
        }
        return null;
    }

    // public static function set_order_data_format($data, $user_id = "") {

    //     $data['product_details'] = json_decode($data['product_details'], true);

    //     return $data;
    // }

    public static function order_data_formatting($data, $multi_data = false, $user_id = "")
    {
        if ($data) {
            $storage = [];
            if ($multi_data == true) {
                foreach ($data as $item) {
                    $storage[] = Helpers::set_order_data_format($item, $user_id);
                }
                $data = $storage;
            } else {
                $data = Helpers::set_order_data_format($data, $user_id);
            }

            return $data;
        }
        return null;
    }

    public static function units()
    {
        $x = ['kg', 'pc', 'gms', 'ltrs', 'bottle'];
        return $x;
    }
    public static function packages()
    {
        $x = ['pcs', 'box', 'bag', 'tin'];
        return $x;
    }

    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $str));
    }

    public static function saveJSONFile($code, $data)
    {
        ksort($data);
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents(base_path('resources/lang/en/messages.json'), stripslashes($jsonData));
    }

    // public static function combinations($arrays)
    // {
    //     $result = [[]];
    //     foreach ($arrays as $property => $property_values) {
    //         $tmp = [];
    //         foreach ($result as $result_item) {
    //             foreach ($property_values as $property_value) {
    //                 $tmp[] = array_merge($result_item, [$property => $property_value]);
    //             }
    //         }
    //         $result = $tmp;
    //     }
    //     return $result;
    // }

    public static function combinations($arrays)
    {
        // Filter out empty or invalid attribute arrays
        $arrays = array_filter($arrays, function ($group) {
            return is_array($group) && count(array_filter($group, 'strlen')) > 0;
        });

        if (empty($arrays)) return [];

        $result = [[]];

        foreach ($arrays as $property => $property_values) {
            // Sanitize property values
            $property_values = array_filter(array_map('trim', $property_values), 'strlen');

            if (empty($property_values)) continue; // Skip empty

            $tmp = [];

            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }

            $result = $tmp;
        }

        // Final validation: return only full-length combinations
        $expectedLength = count($arrays);
        return array_filter($result, function ($combination) use ($expectedLength) {
            return count($combination) === $expectedLength;
        });
    }


    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            $err_keeper[] = ['code' => $index, 'message' => $error[0]];
        }
        return $err_keeper;
    }
    public static function single_error_processor($validator)
    {
        $err_keeper = 'Something went wrong';
        foreach ($validator->errors()->getMessages() as $index => $error) {
            return $error[0];
        }
        return $err_keeper;
    }

    public static function currency_load()
    {
        $default = Helpers::get_business_settings('system_default_currency');
        $current = \session('system_default_currency_info');
        $currentId = null;
        if ($current instanceof Currency) {
            $currentId = $current->id;
        } elseif (is_array($current) && isset($current['id'])) {
            $currentId = $current['id'];
        }

        if (!session()->has('system_default_currency_info') || (string) $default !== (string) $currentId) {
            $id = Helpers::get_business_settings('system_default_currency');
            $currency = null;
            if ($id && Schema::hasTable('currencies')) {
                $currency = Currency::find($id);
            }
            if (!$currency && Schema::hasTable('currencies')) {
                $currency = Currency::where('status', 1)->orderBy('id')->first();
            }
            if ($currency) {
                session()->put('system_default_currency_info', $currency);
                session()->put('currency_code', $currency->code);
                session()->put('currency_symbol', $currency->symbol);
                session()->put('currency_exchange_rate', $currency->exchange_rate);
            } else {
                session()->put('system_default_currency_info', null);
                session()->put('currency_code', session()->get('currency_code', 'USD'));
                session()->put('currency_symbol', session()->get('currency_symbol', '$'));
                session()->put('currency_exchange_rate', session()->get('currency_exchange_rate', 1));
            }
        }
    }

    public static function currency_converter($amount)
    {

        // $currency_model = Helpers::get_business_settings('currency_model');
        // if ($currency_model == 'multi_currency') {

        //     if (session()->has('default')) {
        //         $default = session('default');
        //     } else {
        //         $default = Currency::find(Helpers::get_business_settings('system_default_currency'))->exchange_rate;
        //         session()->put('default', $default);
        //     }

        //     if (session()->has('usd')) {
        //         $usd = session('usd');
        //     } else {
        //         $usd = Currency::where('code', 'USD')->first()->exchange_rate;
        //         session()->put('usd', $usd);
        //     }

        //     $rate = $default / $usd;
        //     $value = floatval($amount) * floatval($rate);
        // } else {
        //     $value = floatval($amount);
        // }

        // return Helpers::set_symbol(round($value, 2)); 

        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            if (session()->has('usd')) {
                $usd = session('usd');
            } else {
                $usdRow = Schema::hasTable('currencies')
                    ? Currency::where(['code' => 'USD'])->first()
                    : null;
                $usd = $usdRow ? $usdRow->exchange_rate : 1;
                session()->put('usd', $usd);
            }
            $my_currency = (float) \session('currency_exchange_rate', 1);
            $usd = (float) $usd;
            $rate = $usd > 0 ? $my_currency / $usd : 1;
        } else {
            $rate = 1;
        }

        return Helpers::set_symbol(round($amount * $rate, 2));
    }

    public static function language_load()
    {
        if (\session()->has('language_settings')) {
            $language = \session('language_settings');
        } else {
            $language = BusinessSetting::where('type', 'language')->first();
            \session()->put('language_settings', $language);
        }
        return $language;
    }

    public static function tax_calculation($price, $tax, $tax_type)
    {
        $amount = ($price / 100) * $tax;
        return $amount;
    }

    public static function get_price_range($product)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if (!empty(json_decode($product->variation))) {
            foreach (json_decode($product->variation) as $key => $variation) {
                if ($lowest_price > $variation->price) {
                    $lowest_price = round($variation->price, 2);
                }
                if ($highest_price < $variation->price) {
                    $highest_price = round($variation->price, 2);
                }
            }
        }

        $lowest_price = Helpers::currency_converter($lowest_price - Helpers::get_product_discount($product, $lowest_price));
        $highest_price = Helpers::currency_converter($highest_price - Helpers::get_product_discount($product, $highest_price));

        if ($lowest_price == $highest_price) {
            return $lowest_price;
        }
        return $lowest_price . ' - ' . $highest_price;
    }

    public static function get_product_discount($product, $price)
    {
        $discount = 0;
        if ($product['discount_type'] == 'percent') {
            $discount = ($price * $product['discount']) / 100;
        } elseif ($product['discount_type'] == 'flat') {
            $discount = $product['discount'];
        }

        return floatval($discount);
    }

    public static function module_permission_check($mod_name)
    {
        $user_role = auth('admin')->user()->role;
        $permission = $user_role->module_access;
        if (isset($permission) && $user_role->status == 1 && in_array($mod_name, (array)json_decode($permission)) == true) {
            return true;
        }

        if (auth('admin')->user()->admin_role_id == 1) {
            return true;
        }
        return false;
    }

    public static function convert_currency_to_usd($price)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            Helpers::currency_load();
            $code = session('currency_code') == null ? 'USD' : session('currency_code');
            if ($code == 'USD') {
                return $price;
            }
            $currency = Currency::where('code', $code)->first();
            $price = floatval($price) / floatval($currency->exchange_rate);

            $usd_currency = Currency::where('code', 'USD')->first();
            $price = $usd_currency->exchange_rate < 1 ? (floatval($price) * floatval($usd_currency->exchange_rate)) : (floatval($price) / floatval($usd_currency->exchange_rate));
        } else {
            $price = floatval($price);
        }

        return $price;
    }

    public static function convert_manual_currency_to_usd($price, $currency = null)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $code = $currency == null ? 'USD' : $currency;
            if ($code == 'USD') {
                return $price;
            }
            $currency = Currency::where('code', $code)->first();
            $price = floatval($price) / floatval($currency->exchange_rate);

            $usd_currency = Currency::where('code', 'USD')->first();
            $price = $usd_currency->exchange_rate < 1 ? (floatval($price) * floatval($usd_currency->exchange_rate)) : (floatval($price) / floatval($usd_currency->exchange_rate));
        } else {
            $price = floatval($price);
        }

        return $price;
    }


    public static function findClause($requirements, $clause)
    {
        $array = [];
        $findarray = null;
        if (is_array($requirements)) {
            foreach ($requirements as $key => $value) {
                if (isset($value['clause']) && $value['clause'] == $clause) {
                    // $findarray = $value;
                    return $value;
                }
            }
            // if($findarray){
            //     $data['clause_no'] = $findarray['clause'];
            //     $data['clause_text'] = $findarray['clause_title'];
            //     $data['cab_self_assessment'] = null;
            //     $data['compliance'] = null;
            //     $data['team_leader_comments'] = null;
            //     $data['other_compliance'] = null;
            //     $data['comment_by'] = null;

            //     return $data;
            // }
        }

        return null;
    }

    public static function findClauseArray($fullArray, &$array = [])
    {
        foreach ($fullArray as $value) {

            $data = [
                'clause_no' => $value['clause_no'] ?? '',
                'clause_text' => $value['clause_text'] ?? '',
                'child_array' => $value['child_array'] ?? '',
                'clause_description' => $value['clause_description'] ?? '',
                'cab_self_assessment' => $value['cab_self_assessment'] ?? '',
                'compliance' => $value['compliance'] ?? '',
                'team_leader_comments' => $value['team_leader_comments'] ?? '',
                'other_compliance' => $value['other_compliance'] ?? '',
                'comment_by' => $value['comment_by'] ?? '',
            ];

            // $isParent = isset($value['child_array']) && (string)$value['child_array'] === '1';
            // $isLeaf   = isset($value['child_array']) && (string)$value['child_array'] === '0';

            $array[] = $data;

            if (!empty($value['items']) && is_array($value['items'])) {
                self::findClauseArray($value['items'], $array);
            }
        }

        return $array;
    }

    public static function order_status_update_message($status)
    {
        if ($status == 'pending') {
            $data = BusinessSetting::where('type', 'order_pending_message')->first()->value;
        } elseif ($status == 'confirmed') {
            $data = BusinessSetting::where('type', 'order_confirmation_msg')->first()->value;
        } elseif ($status == 'processing') {
            $data = BusinessSetting::where('type', 'order_processing_message')->first()->value;
        } elseif ($status == 'out_for_delivery') {
            $data = BusinessSetting::where('type', 'out_for_delivery_message')->first()->value;
        } elseif ($status == 'delivered') {
            $data = BusinessSetting::where('type', 'order_delivered_message')->first()->value;
        } elseif ($status == 'returned') {
            $data = BusinessSetting::where('type', 'order_returned_message')->first()->value;
        } elseif ($status == 'failed') {
            $data = BusinessSetting::where('type', 'order_failed_message')->first()->value;
        } elseif ($status == 'delivery_boy_delivered') {
            $data = BusinessSetting::where('type', 'delivery_boy_delivered_message')->first()->value;
        } elseif ($status == 'del_assign') {
            $data = BusinessSetting::where('type', 'delivery_boy_assign_message')->first()->value;
        } elseif ($status == 'ord_start') {
            $data = BusinessSetting::where('type', 'delivery_boy_start_message')->first()->value;
        } elseif ($status == 'expected_delivery_date') {
            $data = BusinessSetting::where('type', 'delivery_boy_expected_delivery_date_message')->first()->value;
        } elseif ($status == 'canceled') {
            $data = BusinessSetting::where('type', 'order_canceled')->first()->value;
        } else {
            $data = '{"status":"0","message":""}';
        }

        $res = json_decode($data, true);

        if ($res['status'] == 0) {
            return 0;
        }
        return $res['message'];
    }

    /**
     * Device wise notification send
     */
    public static function send_push_notif_to_device($fcm_token, $data)
    {
        $key = BusinessSetting::where(['type' => 'push_notification_key'])->first()->value;
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );

        if (isset($data['order_id']) == false) {
            $data['order_id'] = '';
        }

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "data" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "is_read": 0
              },
              "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "title_loc_key":"' . $data['order_id'] . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function get_notification($user_id)
    {

        $notifications = [];
        $notification_count = 0;
        if ($user_id) {
            $notifications = UserNotification::where('user_id', $user_id)->orderBy('id', 'DESC')->get();
            $notification_count = UserNotification::where('user_id', $user_id)->where('is_read', 0)->count();
        }
        return ['notifications' => $notifications, 'notification_count' => $notification_count];
    }

    public static function product_data_price_format($products)
    {
        if (!is_array($products)) {
            return $products;
        }

        $isSingleProduct = false;

        if (isset($products['variation'])) {
            $products = [$products];
            $isSingleProduct = true;
        }

        $formattedProducts = [];

        foreach ($products as $product) {
            if (!is_array($product)) {
                $formattedProducts[] = $product;
                continue;
            }

            if (!empty($product['variation']) && is_array($product['variation'])) {
                $newVariations = [];

                foreach ($product['variation'] as $variation) {
                    if (isset($variation['price']) && $variation['price'] !== null) {
                        $cleanedPrice = preg_replace('/[^\d.,]/', '', $variation['price']);
                        $cleanedPrice = str_replace(',', '', $cleanedPrice);

                        $originalPrice = (int) $cleanedPrice;
                        $variation['mrp'] = $originalPrice;

                        if (isset($product['discount']) && is_numeric($product['discount'])) {
                            $discount = (float) $product['discount'];
                            $discountedPrice = $originalPrice - ($originalPrice * $discount / 100);

                            // Format discounted price with currency symbol
                            $variation['discounted_price'] = Helpers::set_symbol($discountedPrice);
                        }
                    }

                    $newVariations[] = $variation;
                }



                $product['variation'] = $newVariations;
            }

            $formattedProducts[] = $product;
        }

        return $isSingleProduct ? $formattedProducts[0] : $formattedProducts;
    }


    public static function send_push_notif_to_topic($data)
    {
        $key = BusinessSetting::where(['type' => 'push_notification_key'])->first()->value;

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = [
            "authorization: key=" . $key . "",
            "content-type: application/json",
        ];

        $image = asset('storage/app/public/notification') . '/' . $data['image'];
        $postdata = '{
            "to" : "/topics/sixvalley",
            "data" : {
                "title":"' . $data->title . '",
                "body" : "' . $data->description . '",
                "image" : "' . $image . '",
                "is_read": 0
              },
              "notification" : {
                "title":"' . $data->title . '",
                "body" : "' . $data->description . '",
                "image" : "' . $image . '",
                "title_loc_key":null,
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function get_seller_by_token($request)
    {
        $data = '';
        $success = 0;

        $token = explode(' ', $request->header('authorization'));
        if (count($token) > 1 && strlen($token[1]) > 30) {
            $seller = Seller::where(['auth_token' => $token['1']])->first();
            if (isset($seller)) {


                $seller['bottom_banner'] = $seller['bottom_banner'] ?? "";
                $seller['vacation_start_date'] = $seller['vacation_start_date'] ?? "";
                $seller['vacation_end_date'] = $seller['vacation_end_date'] ?? "";
                $seller['vacation_note'] = $seller['vacation_note'] ?? "";
                $seller->shop;
                $data = $seller;
                $success = 1;
            }
        }

        return [
            'success' => $success,
            'data' => $data
        ];
    }

    public static function remove_dir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") Helpers::remove_dir($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public static function currency_code()
    {
        Helpers::currency_load();
        if (session()->has('currency_code')) {
            return session('currency_code');
        }
        $system_default_currency_info = session('system_default_currency_info');
        if ($system_default_currency_info instanceof Currency) {
            return $system_default_currency_info->code;
        }

        return 'USD';
    }

    public static function get_language_name($key)
    {
        $values = Helpers::get_business_settings('language');
        foreach ($values as $value) {
            if ($value['code'] == $key) {
                $key = $value['name'];
            }
        }

        return $key;
    }

    public static function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        if (is_bool(env($envKey))) {
            $oldValue = var_export(env($envKey), true);
        } else {
            $oldValue = env($envKey);
        }

        if (strpos($str, $envKey) !== false) {
            $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
        } else {
            $str .= "{$envKey}={$envValue}\n";
        }
        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
        return $envValue;
    }

    public static function requestSender()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => route(base64_decode('YWN0aXZhdGlvbi1jaGVjaw==')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $data = json_decode($response, true);
        return $data;
    }

    public static function sales_commission($order)
    {
        $discount_amount = 0;
        if ($order->coupon_code) {
            $coupon = Coupon::where(['code' => $order->coupon_code])->first();
            if ($coupon) {
                $discount_amount = $coupon->coupon_type == 'free_delivery' ? 0 : $order['discount_amount'];
            }
        }
        $order_summery = OrderManager::order_summary($order);
        $order_total = $order_summery['subtotal'] - $order_summery['total_discount_on_product'] - $discount_amount;
        $commission_amount = self::seller_sales_commission($order['seller_is'], $order['seller_id'], $order_total);

        return $commission_amount;
    }

    public static function sales_commission_before_order($cart_group_id, $coupon_discount)
    {
        $carts = CartManager::get_cart($cart_group_id);
        $cart_summery = OrderManager::order_summary_before_place_order($carts, $coupon_discount);
        $commission_amount = self::seller_sales_commission($carts[0]['seller_is'], $carts[0]['seller_id'], $cart_summery['order_total']);

        return $commission_amount;
    }

    public static function seller_sales_commission($seller_is, $seller_id, $order_total)
    {
        $commission_amount = 0;
        if ($seller_is == 'seller') {
            $seller = Seller::find($seller_id);
            if (isset($seller) && $seller['sales_commission_percentage'] !== null) {
                $commission = $seller['sales_commission_percentage'];
            } else {
                $commission = Helpers::get_business_settings('sales_commission');
            }
            $commission_amount = number_format(($order_total / 100) * $commission, 2);
            // dd($order_total);

        }
        return $commission_amount;
    }

    public static function categoryName($id)
    {
        return Category::select('name')->find($id)->name;
    }

    public static function set_symbol($amount)
    {
        $decimal_point_settings = Helpers::get_business_settings('decimal_point_settings');
        $position = Helpers::get_business_settings('currency_symbol_position');
        if (!is_null($position) && $position == 'left') {
            $string = currency_symbol() . '' . number_format($amount, (!empty($decimal_point_settings) ? $decimal_point_settings : 0));
            // return currency_symbol() . ' ' . $amount;
        } else {
            $string = number_format($amount, !empty($decimal_point_settings) ? $decimal_point_settings : 0) . '' . currency_symbol();
        }
        return $string;
    }

    public static function set_cur_symbol($amount)
    {
        $decimal_point_settings = Helpers::get_business_settings('decimal_point_settings');
        $position = Helpers::get_business_settings('currency_symbol_position');
        // if (!is_null($position) && $position == 'left') {
        $string = currency_symbol() . '' . number_format($amount, (!empty($decimal_point_settings) ? $decimal_point_settings : 0));
        // } else {
        //     $string = number_format($amount, !empty($decimal_point_settings) ? $decimal_point_settings : 0) . '' . currency_symbol();
        // }
        return $string;
    }

    public static function pagination_limit()
    {
        $pagination_limit = BusinessSetting::where('type', 'pagination_limit')->first();
        if ($pagination_limit != null) {
            return $pagination_limit->value;
        } else {
            return 25;
        }
    }

    public static function gen_mpdf($view, $file_prefix, $file_postfix)
    {
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'FreeSerif',
            'mode' => 'utf-8',
            'format' => [190, 250],
            'margin_left'   => 5,
            'margin_right'  => 5,
            'margin_top'    => 5,
            'margin_bottom' => 5,
        ]);

        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;

        $mpdf_view = $view->render();

        // Wrap your content in a bordered container
        $html = '
        <div style="border: 2px solid #000; padding: 10px; height: 100%; box-sizing: border-box;">
            ' . $mpdf_view . '
        </div>';

        $mpdf->WriteHTML($html);
        $mpdf->Output($file_prefix . $file_postfix . '.pdf', 'I');
    }
    // public static function gen_mpdf($view, $file_prefix, $file_postfix)
    // {
    //     // $mpdf = new \Mpdf\Mpdf(['default_font' => 'FreeSerif', 'mode' => 'utf-8', 'format' => [190, 250]]);

    //     $mpdf = new \Mpdf\Mpdf([
    //         'default_font' => 'FreeSerif',
    //         'mode' => 'utf-8',
    //         'format' => [190, 250],
    //         'margin_left'   => 0,
    //         'margin_right'  => 0,
    //         'margin_top'    => 0,
    //         'margin_bottom' => 0,
    //         'margin_header' => 0,
    //         'margin_footer' => 0,
    //     ]);

    //     /* $mpdf->AddPage('XL', '', '', '', '', 10, 10, 10, '10', '270', '');*/
    //     $mpdf->autoScriptToLang = true;
    //     $mpdf->autoLangToFont = true;

    //     $mpdf_view = $view;
    //     $mpdf_view = $mpdf_view->render();
    //     $mpdf->WriteHTML($mpdf_view);
    //     $mpdf->Output($file_prefix . $file_postfix . '.pdf', 'D');
    // }

    public static function set_review_data($data)
    {
        $reviews = [];
        if (!empty($data)) {
            foreach ($data as $d_key => $dt) {

                $attachments = [];
                if (!empty($dt['attachment']) && $dt['attachment'] != "") {
                    $attachments = json_decode($dt['attachment'], true);
                    foreach ($attachments as $a_key => $attachment) {
                        $attachments[$a_key] = asset('storage/app/public/review/' . $attachment);
                    }
                }

                $customer = [];
                if (isset($dt['customer']) && !empty($dt['customer'])) {
                    $customer['id'] = $dt['customer']['id'];
                    $customer['name'] = $dt['customer']['f_name'] . ' ' . $dt['customer']['l_name'];
                    $customer['image'] = $dt['customer']['image'] == 'def.png' ? asset('storage/app/' . $dt['customer']['image']) : asset('storage/app/public/profile/' . $dt['customer']['image']);
                } else {
                    $customer['id'] = 0;
                    $customer['name'] = "";
                    $customer['image'] = "";
                }

                $reviews[$d_key]['id'] = $dt['id'];
                $reviews[$d_key]['product_id'] = $dt['product_id'];
                $reviews[$d_key]['customer_id'] = $dt['customer_id'];
                $reviews[$d_key]['delivery_man_id'] = $dt['delivery_man_id'] != "" ? '' : '';
                $reviews[$d_key]['order_id'] = $dt['order_id'] != "" ? '' : '';
                $reviews[$d_key]['comment'] = $dt['comment'] != "" ? $dt['comment'] : '';
                $reviews[$d_key]['attachment'] = $attachments;
                $reviews[$d_key]['rating'] = $dt['rating'];
                $reviews[$d_key]['status'] = $dt['status'];
                $reviews[$d_key]['is_saved'] = $dt['is_saved'];
                $reviews[$d_key]['created_at'] = $dt['created_at'];
                $reviews[$d_key]['updated_at'] = $dt['updated_at'];
                $reviews[$d_key]['customer'] = $customer;
            }
        }
        return $reviews;
    }

    public static function set_shop_data($data)
    {
        $status = '0';
        $countFollowers = 0;
        if (isset($data['id']) && auth()->user()) {
            $first = ShopFollower::where(['user_id' => auth()->user()->id, 'shop_id' => $data['id']])->first();
            $countFollowers = ShopFollower::where(['shop_id' => $data['id']])->count();
            $status = !empty($first) ? '1' : '0';
        }

        $seller_id = $data['seller_id'] ?? 0;
        $product_ids = Product::when($seller_id != 0, function ($query) use ($seller_id) {
            return $query->where(['added_by' => 'seller'])
                ->where('user_id', $seller_id);
        })
            ->pluck('id')->toArray();

        $review_data = Review::whereIn('product_id', $product_ids)->where('status', 1);
        $avg_rating = $review_data->avg('rating');

        $seller = [];
        $seller['id'] = strval($data['id'] ?? "");
        $seller['seller_id'] = strval($data['seller_id'] ?? "");
        $seller['name'] = $data['name'] ?? "";
        $seller['address'] = $data['address'] ?? "";
        $seller['contact'] = $data['contact'] ?? "";
        $seller['image'] = isset($data['image']) && $data['image'] != "" ? asset('storage/app/public/shop/' . $data['image']) : "";
        $seller['bottom_banner'] = $data['bottom_banner'] ?? "";
        $seller['vacation_start_date'] = $data['vacation_start_date'] ?? "";
        $seller['vacation_end_date'] = $data['vacation_end_date'] ?? "";
        $seller['vacation_note'] = $data['vacation_note'] ?? "";
        $seller['vacation_status'] = $data['vacation_status'] ?? 0;
        $seller['temporary_close'] = $data['temporary_close'] ?? 0;
        $seller['created_at'] = $data['created_at'] ?? "";
        $seller['updated_at'] = $data['updated_at'] ?? "";
        $seller['rating'] = number_format($avg_rating, 1);
        $seller['followers'] = strval($countFollowers);
        $seller['is_verified'] = "0";
        $seller['is_following'] = $status;
        $seller['banner'] = isset($data['banner']) ? asset('storage/app/public/shop/banner/' . $data['banner']) : "";
        $seller['product_count'] = strval($data['product_count'] ?? 0);
        return $seller;
    }

    public static function set_order_product_details($product)
    {
        $dt = [];

        $dt['id'] = $product['id']; // 3;
        $dt['added_by'] = $product['added_by'] ?? "admin"; // "admin";
        $dt['user_id'] = $product['user_id']; // 1;
        $dt['name'] = $product['name']; // "demo";
        $dt['slug'] = $product['slug']; // "demo-lxg3sP";
        $dt['product_type'] = $product['product_type']; // "physical";
        $dt['category_ids'] = json_decode($product['category_ids'], true); // "[{\"id\":\"11\",\"position\":1},{\"id\":\"49\",\"position\":2},{\"id\":\"61\",\"position\":3}]";
        $dt['category_id'] = $product['category_id']; // "11";
        $dt['sub_category_id'] = $product['sub_category_id']; // "49";
        $dt['sub_sub_category_id'] = $product['sub_sub_category_id'] ?? ""; // "61";
        $dt['brand_id'] = $product['brand_id']; // 4;
        $dt['unit'] = $product['unit']; // "ltrs";
        $dt['min_qty'] = $product['min_qty']; // 1;
        $dt['refundable'] = $product['refundable']; // 1;
        $dt['digital_product_type'] = $product['digital_product_type'] ?? ""; // null;
        $dt['digital_file_ready'] = $product['digital_file_ready'] ?? ""; // null;
        $dt['images'] = [];

        // $product['images'] ? json_decode($product['images'], true) : []; // "[\"2023-11-16-6556163750546.png\",\"2023-11-16-65561637517d2.png\"]";
        $dt['color_image'] = json_decode($product['color_image'], true); // "[]";
        $dt['thumbnail'] = $product['thumbnail'] == 'def.png' ? asset('storage/app/' . $product['thumbnail']) : asset('storage/app/public/product/thumbnail/' . $product['thumbnail']); // $product['thumbnail']; // "2023-11-16-6556166e17045.png";
        $dt['featured'] = $product['featured']; // 1;
        $dt['flash_deal'] = $product['flash_deal'] ?? ""; // null;
        $dt['video_provider'] = $product['video_provider']; // "youtube";
        $dt['video_url'] = $product['video_url'] ?? ""; // null;
        $dt['colors'] = json_decode($product['colors'], true); // "[]";
        $dt['variant_product'] = $product['variant_product']; // 0;
        $dt['attributes'] = []; // "[]";
        $dt['choice_options'] = json_decode($product['choice_options']); // "[]";
        $dt['variation'] = json_decode($product['variation']); // "[]";
        $dt['published'] = $product['published']; // 0;
        $dt['unit_price'] = (int)$product['unit_price']; // 1;
        $dt['purchase_price'] = (int)$product['purchase_price']; // 2;
        $dt['tax'] = $product['tax']; // 2;
        $dt['tax_type'] = $product['tax_type']; // "percent";
        $dt['tax_model'] = $product['tax_model']; // "include";
        $dt['discount'] = (int)$product['discount']; // 0;
        $dt['discount_type'] = $product['discount_type']; // "percent";
        $dt['current_stock'] = $product['current_stock'] ?? 1; // 9992;
        $dt['minimum_order_qty'] = $product['minimum_order_qty']; // 1;
        $dt['details'] = $product['details'] ?? ""; // null;
        $dt['free_shipping'] = $product['free_shipping']; // 0;
        $dt['attachment'] = $product['attachment'] ?? ""; // null;
        $dt['created_at'] = $product['created_at'] ?? ""; // "2023-11-16T13:16:39.000000Z";
        $dt['updated_at'] = $product['updated_at'] ?? ""; // "2023-11-30T13:54:53.000000Z";
        $dt['status'] = $product['status']; // 1;
        $dt['featured_status'] = $product['featured_status']; // 1;
        $dt['meta_title'] = $product['meta_title'] ?? ""; // null;
        $dt['meta_description'] = $product['meta_description'] ?? ""; // null;
        $dt['meta_image'] = $product['meta_image'] ?? ""; // "def.png";
        $dt['request_status'] = $product['request_status']; // 1;
        $dt['denied_note'] = $product['denied_note'] ?? ""; // null;
        $dt['shipping_cost'] = (int)$product['shipping_cost'] ?? 0; // 3780;
        $dt['multiply_qty'] = $product['multiply_qty'] ?? 0; // 0;
        $dt['temp_shipping_cost'] = (int)$product['temp_shipping_cost'] ?? 0; // null;
        $dt['is_shipping_cost_updated'] = (int)$product['is_shipping_cost_updated'] ?? 0; // null;
        $dt['code'] = $product['code']; // "127070";
        $dt['reviews_count'] = (int)$product['reviews_count']; // 0;
        $dt['translations'] = $product['translations'] ?? []; // [];
        // $dt['reviews'] = $product['reviews'] ?? [];
        return $dt;
    }

    public static function set_order_shipping_data_format($data)
    {
        $data = is_array($data) ? $data : json_decode($data, true);
        $data['id'] = strval($data['id'] ?? "");
        $data['customer_id'] = strval($data['customer_id'] ?? "");
        $data['contact_person_name'] = $data['contact_person_name'] ?? "";
        $data['address_type'] = $data['address_type'] ?? "";
        $data['address'] = $data['address'] ?? "";
        $data['address1'] = $data['address1'] ?? "";
        $data['city'] = $data['city'] ?? "";
        $data['zip'] = $data['zip'] ?? "";
        $data['phone'] = $data['phone'] ?? "";
        $data['alt_phone'] = $data['alt_phone'] ?? "";
        $data['created_at'] = $data['created_at'] ?? "";
        $data['updated_at'] = $data['updated_at'] ?? "";
        $data['state'] = $data['state'] ?? "";
        $data['country'] = $data['country'] ?? "";
        $data['latitude'] = $data['latitude'] ?? "";
        $data['longitude'] = $data['longitude'] ?? "";
        $data['is_billing'] = strval($data['is_billing'] ?? "");
        return $data;
    }

    public static function set_order_orderstatus_data_format($data)
    {
        $dt = [];
        foreach ($data as $ukey => $status) {
            $dt[$ukey]['id'] = $status['id'];
            $dt[$ukey]['order_id'] = $status['order_id'];
            $dt[$ukey]['user_id'] = $status['user_id'];
            $dt[$ukey]['user_type'] = $status['user_type'] ?? "";
            $dt[$ukey]['status'] = BackEndHelper::order_status($status['status'] ?? "");
            $dt[$ukey]['cause'] = $status['cause'] ?? "";
            $dt[$ukey]['created_at'] = $status['created_at'] ?? "";
            $dt[$ukey]['updated_at'] = $status['updated_at'] ?? "";
        }
        return $dt;
    }

    public static function set_refund_request_data($data)
    {
        $dt = [];
        foreach ($data as $ukey => $status) {
            $dt[$ukey]['id'] = $status['id']; // 1,
            $dt[$ukey]['order_details_id'] = $status['order_details_id']; // 20,
            $dt[$ukey]['customer_id'] = $status['customer_id']; // 15,
            $dt[$ukey]['status'] = $status['status'] ?? "pending"; // "pending",
            $dt[$ukey]['amount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($status['amount'])); // 3,
            $dt[$ukey]['product_id'] = $status['product_id']; // 13,
            $dt[$ukey]['order_id'] = $status['order_id']; // 100002,
            $dt[$ukey]['refund_reason'] = $status['refund_reason'] ?? ""; // "Not liked the product",
            $dt[$ukey]['images'] = $status['images'] ?? ""; // "[\"2023-11-29-6566f3a3738e8.png\"]",
            $dt[$ukey]['created_at'] = $status['created_at'] ?? ""; // "2023-11-29T08:17:39.000000Z",
            $dt[$ukey]['updated_at'] = $status['updated_at'] ?? ""; // "2023-11-29T08:17:39.000000Z",
            $dt[$ukey]['approved_note'] = $status['approved_note'] ?? ""; // null,
            $dt[$ukey]['rejected_note'] = $status['rejected_note'] ?? ""; // null,
            $dt[$ukey]['payment_info'] = $status['payment_info'] ?? ""; // null,
            $dt[$ukey]['change_by'] = $status['change_by'] ?? ""; // null

        }
        return $dt;
    }

    public static function set_order_transaction_data($data)
    {
        $dt = [];
        if (!empty($data)) {
            foreach ($data as $t_key => $transaction) {
                $dt[$t_key]['seller_id'] = $transaction['seller_id']; // 1,
                $dt[$t_key]['order_id'] = $transaction['order_id']; // 100010,
                $dt[$t_key]['order_amount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($transaction['order_amount'])); // "468720.00",
                $dt[$t_key]['seller_amount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($transaction['seller_amount'])); // "468720.00",
                $dt[$t_key]['admin_commission'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($transaction['admin_commission'])); // "0.00",
                $dt[$t_key]['received_by'] = $transaction['received_by']; // "admin",
                $dt[$t_key]['status'] = $transaction['status']; // "disburse",
                $dt[$t_key]['delivery_charge'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($transaction['delivery_charge'])); // "5.00",
                $dt[$t_key]['tax'] = $transaction['tax']; // "26040.00",
                $dt[$t_key]['created_at'] = $transaction['created_at']; // "2023-11-24T07:10:56.000000Z",
                $dt[$t_key]['updated_at'] = $transaction['updated_at']; // "2023-11-24T07:10:56.000000Z",
                $dt[$t_key]['customer_id'] = $transaction['customer_id']; // 15,
                $dt[$t_key]['seller_is'] = $transaction['seller_is']; // "admin",
                $dt[$t_key]['delivered_by'] = $transaction['delivered_by']; // "admin",
                $dt[$t_key]['payment_method'] = $transaction['payment_method'] == 'cash_on_delivery' ? 'COD' : ucwords($transaction['payment_method']); // "cash_on_delivery",
                $dt[$t_key]['transaction_id'] = $transaction['transaction_id']; // "5916-vYQMv-1700809856",
                $dt[$t_key]['id'] = $transaction['id']; // 2
            }
        }
        return $dt;
    }

    public static function set_order_data_format($data)
    {

        $data['id'] = $data['id'] ?? 0;
        $data['order_id'] = $data['order_id'] ?? 0;
        $data['product_id'] = $data['product_id'] ?? 0;
        $data['seller_id'] = $data['seller_id'] ?? 0;
        $data['digital_file_after_sell'] = $data['digital_file_after_sell'] ?? false;
        $data['product_details'] = Helpers::set_order_product_details(json_decode($data['product_details'], true));

        $data['qty'] = $data['qty'] ? strval($data['qty']) : "0"; // : 3,
        $data['price'] = $data['price'] ? strval($data['price']) : "0"; // : 1,
        $data['tax'] = $data['tax'] ?? "0"; // : 0.15,
        $data['discount'] = $data['discount'] ? strval($data['discount']) : ""; // : 0.3,
        $data['tax_model'] = $data['tax_model'] ?? ""; // : "exclude",
        $data['delivery_status'] = $data['delivery_status'] ?? ""; // : "pending",
        $data['payment_status'] = $data['payment_status'] ?? ""; // : "paid",
        $data['created_at'] = $data['created_at'] ?? "0"; // : "2023-11-30T06:52:25.000000Z",
        $data['updated_at'] = $data['updated_at'] ?? "0"; // : "2023-12-01T12:24:16.000000Z",
        $data['shipping_method_id'] = $data['shipping_method_id'] ?? 0; // : 0,
        $data['variant'] = $data['variant'] ?? ""; // : "",
        $data['variation'] = json_decode($data['variation'], true) ?? []; // : "[]",
        $data['discount_type'] = $data['discount_type'] ?? ""; // : null,
        $data['is_stock_decreased'] = $data['is_stock_decreased'] ?? 0; // : 1,
        $data['refund_request'] = $data['refund_request'] ?? 0; // : 0,
        $data['customer_id'] = $data['customer_id'] ?? 0; // : "16",
        $data['customer_type'] = $data['customer_type'] ?? ""; // : "customer",
        $data['order_status'] = $data['order_status'] ?? ""; // : "confirmed",
        $data['payment_method'] = $data['payment_method'] ?? ""; // : "razorpay",
        $data['transaction_ref'] = $data['transaction_ref'] ?? ""; // : "RAZORPAYTRANS123456",
        $data['payment_by'] = $data['payment_by'] ?? ""; // : null,
        $data['payment_note'] = $data['payment_note'] ?? ""; // : null,
        $data['order_amount'] = $data['order_amount'] ? strval($data['order_amount']) : "0"; // : 2.85,
        $data['admin_commission'] = $data['admin_commission'] ? strval($data['admin_commission']) : "0"; // : "0.00",
        $data['is_pause'] = $data['is_pause'] ?? "0"; // : "0",
        $data['cause'] = $data['cause'] ?? "0"; // : null,
        $data['shipping_address'] = $data['shipping_address'] ?? 0; // : null,
        $data['discount_amount'] = $data['discount_amount'] ?? "0"; // : 0,
        $data['coupon_code'] = $data['coupon_code'] ?? "0"; // : "0",
        $data['coupon_discount_bearer'] = $data['coupon_discount_bearer'] ?? "0"; // : "inhouse",
        $data['shipping_cost'] = $data['shipping_cost'] ?? "0"; // : 0,
        $data['order_group_id'] = $data['order_group_id'] ?? "0"; // : "16-376671-1701327145",
        $data['verification_code'] = $data['verification_code'] ?? "0"; // : "478253",
        $data['seller_is'] = $data['seller_is'] ?? "0"; // : "admin",
        $data['shipping_address_data'] = Helpers::set_order_shipping_data_format($data['shipping_address_data']); // : null,
        $data['delivery_man_id'] = $data['delivery_man_id'] ?? "0"; // : null,
        $data['deliveryman_charge'] = $data['deliveryman_charge'] ?? "0"; // : 0,
        $data['expected_delivery_date'] = $data['expected_delivery_date'] ?? "0"; // : null,
        $data['order_note'] = $data['order_note'] ?? ""; // : "This is a order note.",
        $data['billing_address'] = $data['billing_address'] ?? 0; // : 33,
        $data['billing_address_data'] = $data['billing_address_data'] ? json_decode($data['billing_address_data']) : []; // : "{\"id\":33,\"customer_id\":16,\"contact_person_name\":\"Tarun Namdev\",\"address_type\":\"home\",\"address\":\"House no 4\",\"address1\":\"Vijay Nagar Indore\",\"city\":\"Indore\",\"zip\":\"488877\",\"phone\":\"7897899876\",\"alt_phone\":\"7897897899\",\"created_at\":\"2023-11-30T06:52:14.000000Z\",\"updated_at\":\"2023-11-30T06:52:14.000000Z\",\"state\":\"Madhya Pradesh\",\"country\":\"India\",\"latitude\":\"25.7569874\",\"longitude\":\"75.3654122\",\"is_billing\":0}",
        $data['order_type'] = $data['order_type'] ?? ""; // : "default_type",
        $data['extra_discount'] = $data['extra_discount'] ?? 0; // : 0,
        $data['extra_discount_type'] = $data['extra_discount_type'] ?? ""; // null,
        $data['checked'] = $data['checked'] ?? 0; // 1,
        $data['shipping_type'] = $data['shipping_type'] ?? ""; // "order_wise",
        $data['delivery_type'] = $data['delivery_type'] ?? ""; // null,
        $data['delivery_service_name'] = $data['delivery_service_name'] ?? ""; // null,
        $data['third_party_delivery_tracking_id'] = $data['third_party_delivery_tracking_id'] ?? ""; // null

        return $data;
    }

    public static function get_time_type($time, $type)
    {
        $singular = ['Day', 'Month', 'Year'];
        $plural = ['Days', 'Months', 'Years'];
        if ($time > 1) {
            $dayType = $time . ' ' . $plural[$type - 1];
        } else {
            $dayType = $time . ' ' . $singular[$type - 1];
        }
        return $dayType;
    }

    public static function get_user_type_plan($typeId)
    {
        $singular = ['Customer', 'Vendor', 'Delivery Boy'];

        $dayType = $singular[$typeId - 1];
        return $dayType;
    }

    public static function get_categories()
    {

        // find what you need
        $find_what_you_need_categories = Category::where('parent_id', 0)->where('home_status', 1)
            ->with(['childes' => function ($query) {
                // $query->withCount(['sub_category_product' => function ($query) {
                //     // return $query->active();
                // }]);
            }])
            ->withCount(['product' => function ($query) {
                // return $query->active();
            }])
            ->limit(6)
            ->orderBy('name', 'ASC')
            ->get()
            ->toArray();

        $get_categories = [];
        foreach ($find_what_you_need_categories as $category) {
            $slice = array_slice($category['childes'], 0, 4);
            $category['childes'] = $slice;
            $get_categories[] = $category;
        }

        $final_category = [];
        foreach ($get_categories as $category) {
            if (count($category['childes']) > 0) {
                $final_category[] = $category;
            }
        }
        return $final_category;
    }

    public static function set_support_model($data)
    {
        if (!empty($data)) {
            foreach ($data as $support) {
                $support->id = strval($support->id ?? ""); // "id": 3,
                $support->customer_id = strval($support->customer_id ?? ""); // "customer_id": 35,
                $support->is_vendor = strval($support->is_vendor ?? ""); // "is_vendor": 1,
                $support->vendor_id = strval($support->vendor_id ?? ""); // "vendor_id": 0,
                $support->order_id = strval($support->order_id ?? ""); // "order_id": 0,
                $support->subject = $support->subject ?? ""; // "subject": "dsfhdfgh",
                $support->type = $support->type ?? ""; // "type": "Website problem",
                $support->priority = $support->priority ?? ""; // "priority": "Urgent",
                $support->description = $support->description ?? ""; // "description": "testing data",
                $support->reply = strval($support->reply ?? ""); // "reply": null,
                $support->status = $support->status ?? ""; // "status": "close",
                $support->created_at = $support->created_at ?? ""; // "created_at": "2024-01-02T15:33:43.000000Z",
                $support->updated_at = $support->updated_at ?? ""; // "updated_at": "2024-01-02T15:37:02.000000Z"
                $support->customer_name = $support->customer->name;
                $support->customer_email = $support->customer->email;
                $support->customer_image = $support->customer->image == 'def.png' ? '' : asset('storage/app/public/profile/' . $support->customer->image);

                unset($support->customer);
            }
        }
        return $data;
    }

    public static function set_support_conv_model($data)
    {
        if (!empty($data)) {
            foreach ($data as $support) {
                $support->id = strval($support->id ?? ""); // "id": 7,
                $support->support_ticket_id = strval($support->support_ticket_id ?? ""); // "support_ticket_id": 3,
                $support->admin_id = strval($support->admin_id ?? ""); // "admin_id": null,
                $support->customer_message = $support->customer_message ?? ""; // "customer_message": "dtyjd tyudtu",
                $support->admin_message = $support->admin_message ?? ""; // "admin_message": null,
                $support->vendor_message = $support->vendor_message ?? ""; // "admin_message": null,
                $support->position = strval($support->position ?? ""); // "position": 0,
                $support->created_at = $support->created_at ?? ""; // "created_at": "2024-01-02T15:35:55.000000Z",
                $support->updated_at = $support->updated_at ?? ""; // "updated_at": "2024-01-02T15:35:55.000000Z"
            }
        }
        return $data;
    }

    public static function countries()
    {
        return Country::select('id', 'name')->get();
    }
}


if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        Helpers::currency_load();
        if (\session()->has('currency_symbol')) {
            return \session('currency_symbol');
        }
        $system_default_currency_info = \session('system_default_currency_info');
        if ($system_default_currency_info instanceof Currency) {
            return $system_default_currency_info->symbol;
        }

        return '$';
    }
}
//formats currency
if (!function_exists('format_price')) {
    function format_price($price)
    {
        return number_format($price, 2) . currency_symbol();
    }
}

function translate($key)
{
    $local = Helpers::default_lang();
    App::setLocale($local);

    try {
        $lang_array = include(base_path('resources/lang/' . $local . '/messages.php'));
        $processed_key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));
        $key = Helpers::remove_invalid_charcaters($key);
        if (!array_key_exists($key, $lang_array)) {
            $lang_array[$key] = $processed_key;
            $str = "<?php return " . var_export($lang_array, true) . ";";
            file_put_contents(base_path('resources/lang/' . $local . '/messages.php'), $str);
            $result = $processed_key;
        } else {
            $result = __('messages.' . $key);
        }
    } catch (\Exception $exception) {
        $result = __('messages.' . $key);
    }

    return $result;
}

function auto_translator($q, $sl, $tl)
{
    $res = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=" . $sl . "&tl=" . $tl . "&hl=hl&q=" . urlencode($q), $_SERVER['DOCUMENT_ROOT'] . "/transes.html");
    $res = json_decode($res);
    return str_replace('_', ' ', $res[0][0][0]);
}

function getLanguageCode(string $country_code): string
{
    $locales = array(
        'af-ZA',
        'am-ET',
        'ar-AE',
        'ar-BH',
        'ar-DZ',
        'ar-EG',
        'ar-IQ',
        'ar-JO',
        'ar-KW',
        'ar-LB',
        'ar-LY',
        'ar-MA',
        'ar-OM',
        'ar-QA',
        'ar-SA',
        'ar-SY',
        'ar-TN',
        'ar-YE',
        'az-Cyrl-AZ',
        'az-Latn-AZ',
        'be-BY',
        'bg-BG',
        'bn-BD',
        'bs-Cyrl-BA',
        'bs-Latn-BA',
        'cs-CZ',
        'da-DK',
        'de-AT',
        'de-CH',
        'de-DE',
        'de-LI',
        'de-LU',
        'dv-MV',
        'el-GR',
        'en-AU',
        'en-BZ',
        'en-CA',
        'en-GB',
        'en-IE',
        'en-JM',
        'en-MY',
        'en-NZ',
        'en-SG',
        'en-TT',
        'en-US',
        'en-ZA',
        'en-ZW',
        'es-AR',
        'es-BO',
        'es-CL',
        'es-CO',
        'es-CR',
        'es-DO',
        'es-EC',
        'es-ES',
        'es-GT',
        'es-HN',
        'es-MX',
        'es-NI',
        'es-PA',
        'es-PE',
        'es-PR',
        'es-PY',
        'es-SV',
        'es-US',
        'es-UY',
        'es-VE',
        'et-EE',
        'fa-IR',
        'fi-FI',
        'fil-PH',
        'fo-FO',
        'fr-BE',
        'fr-CA',
        'fr-CH',
        'fr-FR',
        'fr-LU',
        'fr-MC',
        'he-IL',
        'hi-IN',
        'hr-BA',
        'hr-HR',
        'hu-HU',
        'hy-AM',
        'id-ID',
        'ig-NG',
        'is-IS',
        'it-CH',
        'it-IT',
        'ja-JP',
        'ka-GE',
        'kk-KZ',
        'kl-GL',
        'km-KH',
        'ko-KR',
        'ky-KG',
        'lb-LU',
        'lo-LA',
        'lt-LT',
        'lv-LV',
        'mi-NZ',
        'mk-MK',
        'mn-MN',
        'ms-BN',
        'ms-MY',
        'mt-MT',
        'nb-NO',
        'ne-NP',
        'nl-BE',
        'nl-NL',
        'pl-PL',
        'prs-AF',
        'ps-AF',
        'pt-BR',
        'pt-PT',
        'ro-RO',
        'ru-RU',
        'rw-RW',
        'sv-SE',
        'si-LK',
        'sk-SK',
        'sl-SI',
        'sq-AL',
        'sr-Cyrl-BA',
        'sr-Cyrl-CS',
        'sr-Cyrl-ME',
        'sr-Cyrl-RS',
        'sr-Latn-BA',
        'sr-Latn-CS',
        'sr-Latn-ME',
        'sr-Latn-RS',
        'sw-KE',
        'tg-Cyrl-TJ',
        'th-TH',
        'tk-TM',
        'tr-TR',
        'uk-UA',
        'ur-PK',
        'uz-Cyrl-UZ',
        'uz-Latn-UZ',
        'vi-VN',
        'wo-SN',
        'yo-NG',
        'zh-CN',
        'zh-HK',
        'zh-MO',
        'zh-SG',
        'zh-TW'
    );

    foreach ($locales as $locale) {
        $locale_region = explode('-', $locale);
        if (strtoupper($country_code) == $locale_region[1]) {
            return $locale_region[0];
        }
    }

    return "en";
}

function hex2rgb($colour)
{
    if ($colour[0] == '#') {
        $colour = substr($colour, 1);
    }
    if (strlen($colour) == 6) {
        list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    } elseif (strlen($colour) == 3) {
        list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    } else {
        return false;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return array('red' => $r, 'green' => $g, 'blue' => $b);
}

if (!function_exists('customer_info')) {
    function customer_info()
    {
        return User::where('id', auth('customer')->id())->first();
    }
}

if (!function_exists('order_status_history')) {
    function order_status_history($order_id, $status)
    {
        return OrderStatusHistory::where(['order_id' => $order_id, 'status' => $status])->latest()->pluck('created_at')->first();
    }
}

if (!function_exists('get_shop_name')) {
    function get_shop_name($seller_id)
    {
        return Shop::where(['seller_id' => $seller_id])->first()->name;
    }
}

if (!function_exists('hex_to_rgb')) {
    function hex_to_rgb($hex)
    {
        $result = preg_match('/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i', $hex, $matches);
        $data = $result ? hexdec($matches[1]) . ', ' . hexdec($matches[2]) . ', ' . hexdec($matches[3]) : null;

        return $data;
    }
}

if (!function_exists('get_color_name')) {
    function get_color_name($code)
    {
        return Color::where(['code' => $code])->first()->name;
    }
}
