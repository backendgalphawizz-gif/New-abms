<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\Product;
use DB;

class FlashDealController extends Controller
{
    public function get_flash_deal()
    {
        try {
            $flash_deals = FlashDeal::where('deal_type','flash_deal')
                ->where(['status' => 1])
                ->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))->get();

            $response = [
                'status' => true,
                'message' => 'Flash Deals',
                'data' => $flash_deals
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {

            $response = [
                'status' => false,
                'message' => 'No Deals found',
                'data' => []
            ];
            return response()->json($response, 200);
        }

    }

    public function get_products($deal_id) {
        $p_ids = FlashDealProduct::with(['product'])
                                    ->whereHas('product',function($q){
                                        $q->active();
                                    })
                                    ->where(['flash_deal_id' => $deal_id])
                                    ->pluck('product_id')->toArray();

        if (count($p_ids) > 0) {

            $response = [
                'status' => true,
                'message' => 'Flash Deals Product',
                'data' => Helpers::product_data_formatting(Product::with(['rating'])->whereIn('id', $p_ids)->get(), true)
            ];

            return response()->json($response, 200);
        }
        $response = [
            'status' => false,
            'message' => 'Flash Deals Product',
            'data' => []
        ];
        return response()->json($response, 200);
    }

    public function get_offers($offer_type){
        try {
            $flash_deals = FlashDeal::select(DB::raw('id,title,start_date,end_date,status,featured,banner,slug,created_at,updated_at,deal_type'))
                ->where('deal_type', $offer_type)
                ->where(['status' => 1])
                ->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))->get();

            $response = [
                'status' => true,
                'title' => ucwords(str_replace("-", " ", $offer_type)),
                'message' => 'Flash Deals',
                'data' => $flash_deals
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {

            $response = [
                'status' => false,
                'title' => ucwords(str_replace("-", " ", $offer_type)),
                'message' => 'No Deals found',
                'data' => []
            ];
            return response()->json($response, 200);
        }
    }

}
