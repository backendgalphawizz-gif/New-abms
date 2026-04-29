<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Brand;
use App\Model\Plan;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Translation;
use Rap2hpoutre\FastExcel\FastExcel;

class SubscriptionController extends Controller
{
    public function add_new()
    {
        $br = Plan::latest()->paginate(Helpers::pagination_limit());
        $language=\App\Model\BusinessSetting::where('type','pnc_language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';

        return view('admin-views.plan.add-new', compact('br', 'language', 'default_lang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name.0' => 'required|unique:plans,title',
        ], [
            'name.0.required'   => 'Plan name is required!',
            'name.0.unique'     => 'The plan name has already been taken.',
        ]);

        $brand = new Plan;
        $brand->title = $request->name[array_search('en', $request->lang)];
        $brand->image = ImageManager::upload('brand/', 'png', $request->file('image'));
        $brand->status = 1;
        $brand->description = $request->description ?? "";
        $brand->price = BackEndHelper::currency_to_usd($request->price);
        $brand->type = $request->type ?? "1";
        $brand->time = $request->time ?? "28";
        $brand->user_type = $request->user_type ?? "1";
        $brand->save();

        foreach($request->lang as $index=>$key)
        {
            if($request->name[$index] && $key != 'en')
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Model\Plan',
                        'translationable_id'    => $brand->id,
                        'locale'                => $key,
                        'key'                   => 'name'],
                    ['value'                 => $request->name[$index]]
                );
            }
        }
        Toastr::success('Plan added successfully!');
        return redirect()->route('admin.plan.list');
    }

    /**
     * Brand list show, search
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function list(Request $request)
    {
        $search      = $request['search'];
        $query_param = $search ? ['search' => $request['search']] : '';

        $br = Plan::when($request['search'], function ($q) use($request){
                $key = explode(' ', $request['search']);
                foreach ($key as $value) {
                    $q->Where('title', 'like', "%{$value}%")
                      ->orWhere('id', $value);
                }
            })
            ->orderBy('id', 'DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.plan.list', compact('br','search'));
    }

    /**
     * Export brand list by excel
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function export(){
        $brands = Brand::withCount('brandAllProducts')
            ->with(['brandAllProducts'=> function($query){
                $query->withCount('order_details');
            }])->orderBy('id', 'DESC')->get();

        $data = array();
        foreach($brands as $brand){
            $data[] = array(
                'Brand Name'      => $brand->name,
                'Total Product'   => $brand->brand_all_products_count,
                'Total Order' => $brand->brandAllProducts->sum('order_details_count'),
            );
        }

        return (new FastExcel($data))->download('brand_list.xlsx');
    }

    public function edit($id)
    {
        $b = Plan::where(['id' => $id])->withoutGlobalScopes()->first();
        return view('admin-views.plan.edit', compact('b'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name.0' => 'required|unique:plans,title,'.$id,
        ], [
            'name.0.required'   => 'Plan title is required!',
            'name.0.unique'     => 'The plan title has already been taken.',
        ]);

        $brand = Plan::find($id);
        $brand->title = $request->name[array_search('en', $request->lang)];
        if ($request->has('image')) {
            $brand->image = ImageManager::update('brand/', $brand['image'], 'png', $request->file('image'));
        }
        $brand->description = $request->description ?? "";
        $brand->price = BackEndHelper::currency_to_usd($request->price);
        $brand->type = $request->type ?? "1";
        $brand->time = $request->time ?? "28";
        $brand->user_type = $request->user_type ?? "1";
        $brand->save();
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\Plan',
                        'translationable_id' => $brand->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
        }

        Toastr::success('Plan updated successfully!');
        return redirect()->route('admin.plan.list');
    }

    public function status_update(Request $request)
    {
        $brand = Brand::find($request['id']);
        $brand->status = $request['status'];

        if($brand->save()){
            $success = 1;
        }else{
            $success = 0;
        }
        return response()->json([
            'success' => $success,
        ], 200);
    }

    public function delete(Request $request)
    {
        $translation = Translation::where('translationable_type','App\Model\Brand')
                                    ->where('translationable_id',$request->id);
        $translation->delete();
        $brand = Brand::find($request->id);
        ImageManager::delete('/brand/' . $brand['image']);
        $brand->delete();
        return response()->json();
    }
}
