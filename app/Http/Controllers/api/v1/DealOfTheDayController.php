<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\DealOfTheDay;
use App\Model\Product;
use App\CPU\Helpers;

class DealOfTheDayController extends Controller
{
    public function get_deal_of_the_day_product(Request $request)
    {
        $deal_of_the_day = DealOfTheDay::where('deal_of_the_days.status', 1)->get();
        
        if($deal_of_the_day->isNotEmpty()){
            foreach ($deal_of_the_day as $key => $deal) {
                $product = Product::active()->find($deal->product_id);
                
                if(!isset($product))
                {
                    $product = Product::active()->inRandomOrder()->first();
                }
                // $deal->product = [Helpers::product_data_formatting($product)];
            }

            $response = [
                'status' => true,
                'title' => 'Daily Deals',
                'message' => 'Deal Of the day',
                'data' => $deal_of_the_day
            ];

            return response()->json($response, 200);
        }else{

            $response = [
                'status' => true,
                'message' => 'Deal Of the day',
                'data' => []
            ];

            return response()->json($response, 200);

            $product = Product::active()->inRandomOrder()->first();
            $product = Helpers::product_data_formatting($product);
            return response()->json($product, 200);
        }
        
    }
}
