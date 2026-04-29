<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\State;
use App\Model\City;
use App\Model\Area;
use App\Model\Zipcode;
use App\Model\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class AreaController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories = Area::select('areas.*', 'cities.name as city', 'areas.created_at')
                ->leftJoin('cities', 'areas.city_id', '=', 'cities.id')
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('areas.name', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $categories = Area::select('areas.*', 'cities.name as city', 'areas.created_at')
                ->leftJoin('cities', 'areas.city_id', '=', 'cities.id');
        }

        $categories = $categories->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.area.view', compact('categories', 'search'));
    }
    public function city(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories = City::with('state')->where(['country_id' => 101])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                        // $q->orWhere('state', 'like', "%{$value}%");
                    }
                })
                ->orderBy('name', 'asc');
            $query_param = ['search' => $request['search']];
        } else {
            $categories = City::with('state')->where(['country_id' => 101])->orderBy('name', 'asc');
        }

        $states = State::where('country_id', 101)->orderBy('name', 'asc')->get();

        $categories = $categories->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.area.cityview', compact('categories', 'search', 'states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'zipcode' => 'required|numeric|digits_between:5,6|unique:zipcode',
            'area' => 'required'
        ]);

        $category = new Area();
        $category->city_id = $request->city_id;
        $category->name = $request->area;
        // $category->bonus = $request->bonus;
        $category->save();


        Toastr::success('Area added successfully!');
        return back();
    }
    public function citystore(Request $request)
    {
        $request->validate([
            'city' => 'required|unique:cities,name',
            'state' => 'required|'
        ]);

        $state = State::find($request->state);

        $category = new City;
        $category->name = ucfirst($request->city);
        $category->state_id = $request->state;
        $category->state_code = $state['iso2'] ?? 'RJ';
        $category->country_id = $state['country_id'] ?? 101;
        $category->country_code = 'IN';
        $category->save();


        Toastr::success('City added successfully!');
        return back();
    }

    // public function edit(Request $request, $id)
    // {
    //     $category = Category::with('translations')->withoutGlobalScopes()->find($id);
    //     return view('admin-views.category.category-edit', compact('category'));
    // }

    public function update(Request $request)
    {
        $request->validate([
            // 'zipcode' => 'required|numeric|digits_between:5,6',
            'area' => 'required'
        ]);
        $category = Area::find($request->id);
        $category->name = $request->area;
        // $category->city_id = $request->city_id;
        // $category->bonus = $request->bonus;
        $category->save();

        Toastr::success('Area updated successfully!');
        return back();
    }
    public function cityupdate(Request $request)
    {
        $request->validate([
            'city' => 'required',
        ]);
        $category = City::find($request->id);
        $category->name = $request->city;
        $category->save();

        Toastr::success('City updated successfully!');
        return back();
    }

    public function citydelete(Request $request)
    {

        City::destroy($request->id);

        return response()->json();
    }
    public function delete(Request $request)
    {

        Area::destroy($request->id);

        return response()->json();
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::where('position', 0)->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }

    public function getCity(Request $request)
    {
        $search = $request->get('search');
        $cities = City::where('name', 'like', "%{$search}%")
            ->where('country_id', 101)
            ->get(['id', 'name']);
        return response()->json($cities);
    }

    public function getCityState(Request $request)
    {
        $stateId = $request->state_id;

        $cities = City::where('state_id', $stateId)
            // ->where('country_id', 101) // India
            ->select('id', 'name')
            ->get();

        return response()->json($cities);
    }
    public function getAreaCity(Request $request)
    {
        $stateId = $request->city_id;

        $cities = Area::where('city_id', $stateId)
            // ->where('country_id', 101) // India
            ->select('id', 'name')
            ->get();

        return response()->json($cities);
    }

    public function searchArea(Request $request)
    {
        $search = trim($request->input('search'));

        if ($search === '') {
            $areas = Area::select('id', 'name')->limit(20)->get();
        } else {
            $areas = Area::select('id', 'name')
                ->where('name', 'like', '%' . $search . '%')
                ->limit(20)
                ->get();
        }

        return response()->json($areas);
    }




    public function status(Request $request)
    {
        $category = Category::find($request->id);
        $category->home_status = $request->home_status;
        $category->save();
        // Toastr::success('Service status updated!');
        // return back();
        return response()->json([
            'success' => 1,
        ], 200);
    }


    public function bulk_import_index()
    {
        return view('admin-views.area.bulk-import');
    }

    public function bulk_store_areas(Request $request)
    {
        $request->validate([
            'areas_file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $collections = (new FastExcel)->import($request->file('areas_file'));
        } catch (\Exception $e) {
            Toastr::error('Invalid file format. Please upload a valid Excel/CSV file.');
            return back();
        }

        if (empty($collections) || count($collections) === 0) {
            Toastr::error('Uploaded file contains no data.');
            return back();
        }

        $data = [];
        $errors = []; 

        foreach ($collections as $row) {
            $normalized = [];
            foreach ($row as $key => $value) {
                $k = (string) Str::of($key)->trim()->lower()->replace("\u{FEFF}", '');
                $normalized[$k] = $value;
            }

            if (empty($normalized['area']) || empty($normalized['city'])) {
                $errors[] = "Missing required fields (area, city) in row: " . json_encode($row);
                continue;
            }

            $cityName = trim($normalized['city']);
            $city = City::where('name', $cityName)->first();

            if (!$city) {
                $errors[] = 'City "' . $cityName . '" not found (Area: ' . $normalized['area'] . ')';
                continue; 
            }

            $areaName = trim($normalized['area']);

            $exists = Area::where('name', $areaName)
                ->where('city_id', $city->id)
                ->exists();

            if ($exists) {
                $errors[] = 'Duplicate found: Area "' . $areaName . '" already exists in City "' . $cityName . '"';
                continue; 
            }

            $data[] = [
                'name'       => $areaName,
                'city_id'    => $city->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($data)) {
            Area::insert($data);
            Toastr::success(count($data) . ' new areas imported successfully!');
        }

        if (!empty($errors)) {
            Toastr::error(
                "Some rows were skipped:<br>" . implode('<br>', $errors),
                'Import Issues',
                ["timeOut" => 20000, "extendedTimeOut" => 5000, "closeButton" => true, "progressBar" => true, "escapeHtml" => false]
            );
        }

        return redirect()->route('admin.area.view');
    }

    // public function bulk_store_areas(Request $request)
    // {
    //     $request->validate([
    //         'areas_file' => 'required|mimes:xlsx,xls,csv|max:5120',
    //     ]);

    //     try {
    //         $collections = (new FastExcel)->import($request->file('areas_file'));
    //     } catch (\Exception $e) {
    //         Toastr::error('Invalid file format. Please upload a valid Excel/CSV file.');
    //         return back();
    //     }

    //     if (empty($collections) || count($collections) === 0) {
    //         Toastr::error('Uploaded file contains no data.');
    //         return back();
    //     }

    //     $data = [];
    //     foreach ($collections as $row) {
    //         // normalize keys
    //         $normalized = [];
    //         foreach ($row as $key => $value) {
    //             $k = (string) Str::of($key)->trim()->lower()->replace("\u{FEFF}", '');
    //             $normalized[$k] = $value;
    //         }

    //         if (empty($normalized['area']) || empty($normalized['city'])) {
    //             Toastr::error('Required fields (area, city) missing in one or more rows.');
    //             return back();
    //         }

    //         $cityName = trim($normalized['city']);
    //         $city = City::where('name', $cityName)->first();
    //         if (!$city) {
    //             Toastr::error('City "' . $cityName . '" not found in database. Please add it first.');
    //             return back();
    //         }

    //         $areaName = trim($normalized['area']);

    //         // check duplicate area for the same city
    //         $exists = Area::where('name', $areaName)
    //             ->where('city_id', $city->id)
    //             ->exists();

    //         if (!$exists) {
    //             $data[] = [
    //                 'name'       => $areaName,
    //                 'city_id'    => $city->id,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         }
    //     }

    //     if (!empty($data)) {
    //         Area::insert($data);
    //     }

    //     Toastr::success(count($data) . ' new areas imported successfully!');
    //     return redirect()->route('admin.area.view');
    // }
}
