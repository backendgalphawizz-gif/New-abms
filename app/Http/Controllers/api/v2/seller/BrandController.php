<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function getBrands()
    {
        try {
            $brands = Brand::all();
        } catch (\Exception $e) {
        }
        $response = ['status' => true, 'message' => 'Get Brand Lists', 'data' => $brands];
        return response()->json($response, 200);
    }

}
