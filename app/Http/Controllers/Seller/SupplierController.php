<?php

namespace App\Http\Controllers\Seller;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\City;
use App\Model\Coupon;
use App\Model\Seller;
use App\Model\State;
use App\Model\Area;
use App\Model\Supplier;
use App\Model\Country;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use function App\CPU\translate;

class SupplierController extends Controller
{
    public function add_new(Request $request)
    {
        $countries = Country::select('name','id')->get()->pluck('name','id');
        $states = [];
        $cities = [];

        $query_param = [];
        $search = $request['search'];
        $coupons = Supplier::whereIn('seller_id', [auth('seller')->user()->id, '0'])
                ->when(isset($request['search']) && !empty($request['search']), function($query) use($request){
                    $key = explode(' ', $request['search']);
                    foreach ($key as $value) {
                        $query->where('name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    }
                })
                ->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('seller-views.supplier.add-new', compact('coupons', 'search', 'countries', 'states', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|min:10|max:10',
            'email' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'pin_code' => 'required'
        ]);

        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->country_id = $request->country;
        $supplier->state_id = $request->state;
        $supplier->city_id = $request->city;
        $supplier->address = $request->address;
        $supplier->pincode = $request->pin_code;
        $supplier->seller_id = auth('seller')->user()->id;
        $supplier->save();

        Toastr::success(\App\CPU\translate('supplier_added_successfully!'));
        return back();
    }

    public function edit(Request $request, $id)
    {
        $coupon = Supplier::find($id);

        $countries = Country::select('name','id')->get()->pluck('name','id');
        $states = State::select('name','id')->where('country_id', $coupon->country_id)->get()->pluck('name','id');
        $cities = City::select('name','id')->where('state_id', $coupon->state_id)->get()->pluck('name','id');

        $query_param = [];
        $search = $request['search'];
        $coupons = Supplier::whereIn('seller_id', [auth('seller')->user()->id, '0'])
                ->when(isset($request['search']) && !empty($request['search']), function($query) use($request){
                    $key = explode(' ', $request['search']);
                    foreach ($key as $value) {
                        $query->where('name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    }
                })
                ->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        if(!$coupon) {
            Toastr::error('Invalid Coupon!');
            return redirect()->route('seller.supplier.add-new');
        }
        $customers = User::where('id', '<>', '0')->get();
        return view('seller-views.supplier.add-new', compact('coupon','coupons', 'customers', 'countries', 'states', 'cities', 'search'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|min:10|max:10',
            'email' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'pin_code' => 'required'
        ]);

        $supplier = Supplier::find($id);
        $supplier->name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->country_id = $request->country;
        $supplier->state_id = $request->state;
        $supplier->city_id = $request->city;
        $supplier->address = $request->address;
        $supplier->pincode = $request->pin_code;
        $supplier->save();

        Toastr::success(\App\CPU\translate('supplier_updated_successfully'));
        return redirect()->route('seller.supplier.add-new');
    }

    public function status_update(Request $request)
    {
        $coupon = Coupon::where('coupon_bearer','seller')->whereIn('seller_id', [auth('seller')->user()->id, '0'])->find($request->id);
        if(!$coupon){
            Toastr::warning(\App\CPU\translate('coupon_not_found'));
        }
        $coupon->status = $request->status;
        $coupon->save();
        Toastr::success(\App\CPU\translate('coupon_status_updated'));
        return back();
    }

    public function quick_view_details(Request $request)
    {
        $coupon = Coupon::whereIn('seller_id',[auth('seller')->user()->id, '0'])->find($request->id);

        return response()->json([
            'view' => view('seller-views.supplier.details-quick-view', compact('coupon'))->render(),
        ]);
    }

    public function delete($id)
    {
        $coupon = Coupon::where(['added_by'=>'seller', 'coupon_bearer'=>'seller'])
        ->whereIn('seller_id', [auth('seller')->user()->id, '0'])->find($id);
        if(!$coupon){
            Toastr::warning(\App\CPU\translate('coupon_not_found'));
        }
        $coupon->delete();
        Toastr::success(\App\CPU\translate('coupon_deleted_successfully'));
        return back();
    }

    public function getState(Request $request) {
        $country_id = $request->input('country_id');
        // $country = Country::find($country_id);
        $states = State::where('country_id', $country_id)->orderBy('name', 'ASC')->get();

        $response = ['status' => true, 'messsage' => 'All states list', 'data' => $states];
        return response()->json($response);
    }

    public function getCity(Request $request) {
        $state_id = $request->input('state_id');
        $cities = City::where('state_id', $state_id)->orderBy('name', 'ASC')->get();

        $response = ['status' => true, 'messsage' => 'All city list', 'data' => $cities];
        return response()->json($response);
    }
    // public function getArea(Request $request) {
    //     $cityId = $request->input('city_id');
    //     $areas = Area::where('city_id', $cityId)->orderBy('name', 'ASC')->get();

    //     $response = ['status' => true, 'messsage' => 'All area list', 'data' => $areas];
    //     return response()->json($response);
    // }
    public function getArea(Request $request) {
    $cityId = $request->input('city_id');
 
    $areas = Area::where('city_id', $cityId)->orderBy('name', 'ASC')->get();

    $response = ['status' => true, 'messsage' => 'All area list', 'data' => $areas];
        return response()->json($response);
    }




}
