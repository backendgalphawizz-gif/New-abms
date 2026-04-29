<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\FlashDeal;
use Illuminate\Http\Request;
use App\Model\FlashDealProduct;
use App\Model\Product;

class DealController extends Controller
{
    public function get_featured_deal()
    {
        $featured_deal = FlashDeal::where(['status' => 1])
            ->where(['deal_type' => 'feature_deal'])->get();

        // $p_ids = array();
        if ($featured_deal->isNotEmpty()) {
            foreach($featured_deal as $deal) {
                $p_ids = FlashDealProduct::with(['product'])
                    ->whereHas('product', function ($q) {
                        $q->active();
                    })
                    ->where(['flash_deal_id' => $deal->id])
                    ->pluck('product_id')->toArray();
                $deal->products = Helpers::product_data_formatting(Product::with(['rating','tags'])->whereIn('id', $p_ids)->get(), true);
            }
        }
        $response = [
            'status' => true,
            'message' => "Offer Lists",
            'data' => $featured_deal // Helpers::product_data_formatting(Product::with(['rating','tags'])->whereIn('id', $p_ids)->get(), true)
        ];
        return response()->json($response, 200);
    }

}
