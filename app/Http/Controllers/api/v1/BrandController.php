<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\BrandManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function get_brands()
    {
        try {
            $brands = BrandManager::get_active_brands();
            foreach ($brands as $key => $brand) {
                $brand->image = asset('storage/app/public/brand/' . $brand->image);
            }
        } catch (\Exception $e) {
        }



        $response = [
            'status' => true,
            'message' => "Brand Lists",
            'data' => $brands
        ];

        return response()->json($response, 200);
    }

    public function get_products($brand_id)
    {
        try {
            $products = BrandManager::get_products($brand_id);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }

        return response()->json($products,200);
    }

    public function send_notification(Request $request) {
        OrderManager::send_notification($request->input('fcm_id'));
    }

}
