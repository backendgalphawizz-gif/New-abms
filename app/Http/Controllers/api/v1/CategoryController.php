<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CategoryManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function get_categories(Request $request)
    {
        try {

            $categoryIds = [];
            if($request->input('vendor_id') && $request->input('vendor_id') != "") {
                $categoryIds = Product::select('category_id')->where(['user_id' => $request->input('vendor_id'), 'added_by' => 'seller'])->get()->pluck('category_id')->toArray();
            }
            if($request->input('is_home') == 1) {
                $categories = Category::withCount(['product'=>function($qc1){
                        $qc1->where(['status'=>'1']);
                    }])->with(['childes.childes'])->where(['position' => 0, 'home_status' => 1])->priority()->get();
            } else {

                $vendorId = $request->input('vendor_id');

                if(!empty($categoryIds)) {
                    $categoryIds = array_values(array_unique($categoryIds));
                    $categories = Category::withCount(['product'=>function($qc1) use($vendorId){
                        $qc1->where(['status'=>'1', 'user_id' => $vendorId]);
                    }])->with(['childes' => function ($qc2) use($vendorId) {
                        $qc2->with(['childes' => function ($qc3) use($vendorId) {
                            $qc3->withCount(['sub_sub_category_product'=>function($qc1) use($vendorId){
                                $qc1->where(['status'=>'1', 'user_id' => $vendorId]);
                            }])->where('position', 2);
                        }])->withCount(['sub_category_product'=>function($qc1) use($vendorId){
                            $qc1->where(['status'=>'1', 'user_id' => $vendorId]);
                        }])->where('position', 1);
                    }, 'childes.childes'])->whereIn('id', $categoryIds)
                    ->where(['position' => 0])
                    ->priority()
                    ->get();

                } else {

                    $categories = Category::withCount(['product'=>function($qc1){
                        $qc1->where(['status'=>'1']);
                    }])->with(['childes' => function ($qc2) {
                        $qc2->with(['childes' => function ($qc3) {
                            $qc3->withCount(['sub_sub_category_product'=>function($qc1){
                                $qc1->where(['status'=>'1']);
                            }])->where('position', 2);
                        }])->withCount(['sub_category_product'=>function($qc1){
                            $qc1->where(['status'=>'1']);
                        }])->where('position', 1);
                    }, 'childes.childes'])
                    ->where(['position' => 0])
                    ->priority()
                    ->get();

                    $categories = Category::withCount(['product'=>function($qc1){
                        $qc1->where(['status'=>'1']);
                    }]) ->with(['childes' => function ($qc2) {
                        $qc2->with(['childes' => function ($qc3) {
                            $qc3->withCount(['sub_sub_category_product'=>function($qc1){
                                $qc1->where(['status'=>'1']);
                            }])->where('position', 2);

                        }])->withCount(['sub_category_product'=>function($qc1){
                            $qc1->where(['status'=>'1']);
                        }])->where('position', 1);
                    }, 'childes.childes'])->where(['position' => 0])->priority()->get();
                }
            }

            $cats = [];
            
            foreach ($categories as $key => $category) {
                
                $category->icon = $category->icon!= "" ? asset('storage/app/public/category/' . $category->icon) : "";
                if($category->childes->isNotEmpty()) {
                    $child = [];
                    foreach ($category->childes as $ckey => $cat) {
                        $cat->icon = $cat->icon != "" ? asset('storage/app/public/category/' . $cat->icon) : "";
                        if($cat->sub_category_product_count > 0) {
                            $child[] = $cat;
                        }
                        // if($cat->childes->isNotEmpty()) {
                        //     foreach ($cat->childes as $cckey => $dt_cat) {
                        //         $dt_cat->icon = $dt_cat->icon !="" ? asset('storage/app/public/category/' . $dt_cat->icon) : "";
                        //     }
                        // }
                    }
                    unset($category->childes);
                    $category->childes = $child;
                }
// dd($categories);
                if($category->product_count > 0) {
                    $cats[] = $category;
                }

            }

            $response = [
                'status' => true,
                'message' => "Category Lists",
                'data' => $cats
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'status' => true,
                'message' => $e->getMessage(),
                'data' => []
            ];
            return response()->json($response, 200);
        }
    }

    public function get_products($id)
    {
        return response()->json(Helpers::product_data_formatting(CategoryManager::products($id), true), 200);
    }
}
