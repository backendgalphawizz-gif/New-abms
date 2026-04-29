<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\SchemeArea;
use App\Model\Scheme;
use App\Model\Scope;
use App\Model\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;

class SchemeController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $categories = Scheme::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $categories = Scheme::query();
        }

        $categories = $categories->orderBy('id','DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.scheme.view', compact('categories','search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ], [
            'title.required' => 'Scheme title is required!'
        ]);

        $scheme = new Scheme();
        $scheme->title = $request->input('title');
        $scheme->code = $request->input('code') ?? null;
        $scheme->save();

        Toastr::success('Scheme added successfully!');
        return back();
    }
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ], [
            'title.required' => 'Scheme title is required!'
        ]);

        $scheme = Scheme::find($request['id']);
        $scheme->title = $request->input('title');
        $scheme->code = $request->input('code') ?? null;
        $scheme->save();

        Toastr::success('Scheme update successfully!');
        return back();
    }

    public function delete(Request $request){
        $scheme = Scheme::find($request['id']);
        $scheme->delete();

        return response()->json([
            'success' => 1,
        ], 200);
    }


    public function areaIndex(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $categories = SchemeArea::with('scheme')
                ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $categories = SchemeArea::with('scheme');
        }

        $categories = $categories->orderBy('id','DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        $scheme = Scheme::get();
        return view('admin-views.scheme.areaview', compact('categories','scheme','search'));
    }
   
    
    public function areastore(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'scheme_id' => 'required'
        ], [
            'title.required' => 'Scheme Area title is required!',
            'scheme_id.required' => 'Scheme is required!'
        ]);

        $scheme = new SchemeArea();
        $scheme->scheme_id = $request->input('scheme_id');
        $scheme->title = $request->input('title');
        $scheme->code = $request->input('code') ?? null;
        $scheme->save();

        Toastr::success('Area added successfully!');
        return back();
    }

    public function areaUpdate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'scheme_id' => 'required'
        ], [
            'title.required' => 'Scheme Area title is required!',
            'scheme_id.required' => 'Scheme is required!'
        ]);

        $scheme = SchemeArea::find($request['id']);
        $scheme->scheme_id = $request->input('scheme_id');
        $scheme->title = $request->input('title');
        $scheme->code = $request->input('code') ?? null;
        $scheme->save();

        Toastr::success('Area update successfully!');
        return back();
    }

    public function areaDelete(Request $request){
        $scheme = SchemeArea::find($request['id']);
        $scheme->delete();

        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function scopeIndex(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $categories = Scope::with('scheme','area')
                ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $categories = Scope::with('scheme','area');
        }

        $categories = $categories->orderBy('id','DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        $scheme = Scheme::get();
        return view('admin-views.scheme.scopeview', compact('categories','scheme','search'));
    }
   
    
    public function scopestore(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'scheme_id' => 'required',
            'area_id' => 'required'
        ], [
            'title.required' => 'Scope title is required!',
            'scheme_id.required' => 'Scheme is required!',
            'area_id.required' => 'Area is required!'
        ]);

        $scheme = new Scope();
        $scheme->scheme_id = $request->input('scheme_id');
        $scheme->area_id = $request->input('area_id');
        $scheme->title = $request->input('title');
        // $scheme->code = $request->input('code') ?? null;
        $scheme->save();

        Toastr::success('Scope added successfully!');
        return back();
    }

    public function scopeUpdate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            // 'scheme_id' => 'required',
            // 'area_id' => 'required',
        ], [
            'title.required' => 'Scope title is required!',
            // 'scheme_id.required' => 'Scheme is required!',
            // 'area_id.required' => 'Area is required!'
        ]);

        $scheme = Scope::find($request['id']);
        // $scheme->scheme_id = $request->input('scheme_id');
        // $scheme->area_id = $request->input('area_id');
        $scheme->title = $request->input('title');
        // $scheme->code = $request->input('code') ?? null;
        $scheme->save();

        Toastr::success('Scope update successfully!');
        return back();
    }

    public function scopeDelete(Request $request){
        $scheme = Scope::find($request['id']);
        $scheme->delete();

        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function getAreas(Request $request, $id){
        $area = SchemeArea::where('scheme_id',$id)->get();
        return response()->json($area);

    }
}
