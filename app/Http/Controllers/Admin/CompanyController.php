<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Area;
use App\Model\City;
use App\Model\State;
use App\Model\CompanyProfile;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Subscription;
use App\Model\BusinessSetting;
use App\Model\Customer;
use App\Model\DeliveryMan;
use App\Model\LoginReport;
use App\Model\Seller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\CPU\ImageManager;

class CompanyController extends Controller {
    // public function index(Request $request)
    // {
    //     $query_param = [];
    //     $search = $request['search'];
    //     if ($request->has('search')) {
    //         $key = explode(' ', $request['search']);
    //         $customers = CompanyProfile::with(['user'])
    //             ->where(function ($q) use ($key) {
    //                 foreach ($key as $value) {
    //                     $q->orWhere('name', 'like', "%{$value}%")
    //                         // ->orWhere('l_name', 'like', "%{$value}%")
    //                         ->orWhere('phone', 'like', "%{$value}%");
    //                     // ->orWhere('email', 'like', "%{$value}%");
    //                 }
    //             });
    //         $query_param = ['search' => $request['search']];
    //     } else {
    //         $customers = CompanyProfile::with(['user']);
    //     }
    //     $companies = $customers->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
    //     return view('admin-views.company.list', compact('companies', 'search'));
    // }

    public function index(Request $request)
{
    $search = $request->input('search');

    $customers = CompanyProfile::with('user')
        ->leftJoin('users', 'users.id', '=', 'company_profiles.user_id')
        ->select('company_profiles.*');

    if (!empty($search)) {
        $customers->where(function ($q) use ($search) {

            $q->orWhere('company_profiles.name', 'LIKE', "%{$search}%")     
              ->orWhere('users.name', 'LIKE', "%{$search}%")               
              ->orWhere('users.email', 'LIKE', "%{$search}%")             
              ->orWhere('users.phone', 'LIKE', "%{$search}%");            

        });
    }

    $companies = $customers
        ->orderBy('company_profiles.id', 'DESC')
        ->paginate(Helpers::pagination_limit())
        ->appends(['search' => $search]);

    return view('admin-views.company.list', compact('companies', 'search'));
}


    public function show(Request $request, CompanyProfile $companyProfile){
        return view('admin-views.company.show', [ 'company' => $companyProfile ]);
    }

    public function status_update(Request $request){
        CompanyProfile::where(['id' => $request['id']])->update([
            'status' => $request['status'],
            'remark' => $request['remarks']
        ]);

        return response()->json([], 200);
    }

    public function store(Request $request)
    {

        // dd($request);
        $request->validate([
            'f_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone' => 'required|unique:users',
            // 'image' => 'required'

        ], [
            'f_name.required' => 'First name is required!',
            'email.required' => 'Email id is Required',
            // 'image.required' => 'Image is Required',

        ]);
        if ($request->confirm_password != $request->password) {
            Toastr::error('Confirm password and password does not match');
            return redirect()->back();
        }

        DB::table('users')->insert([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'area' => $request->area,
            'state' => $request->state,
            // 'address' => $request->address,
            'street_address' => $request->address,
            'age' => $request->age,
            'gender' => $request->gender,
            'password' => bcrypt($request->password),
            'is_active'=>1,
            'image' => ImageManager::upload('profile/', 'png', $request->file('image')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success('User added successfully!');
        return redirect()->route('admin.customer.list');
    }


    public function bulk_import_index()
    {
        return view('admin-views.customer.bulk-import');
    }

    public function bulk_store_customers(Request $request)
    {
        try {
            $collections = (new FastExcel)->import($request->file('customers_file'));
        } catch (\Exception $exception) {
            Toastr::error('You have uploaded a wrong format file, please upload the right file.');
            return back();
        }

        $data = [];
        foreach ($collections as $row) {
            if (empty($row['customer_name']) || empty($row['phone'])) {
                Toastr::error('Required fields missing in one or more rows.');
                return back();
            }

            $areaId = null;
            if (!empty($row['area'])) {
                $area = Area::where('name', $row['area'])->first();
                $areaId = $area?->id;
            }

            $cityId = null;
            if (!empty($row['city'])) {
                $city = City::where('name', $row['city'])->first();
                $cityId = $city?->id;
            }

            $stateId = null;
            if (!empty($row['state'])) {
                $state = State::where('name', $row['state'])->first();
                $stateId = $state?->id;
            }

            $data[] = [
                'f_name' => $row['customer_name'],
                'phone' => $row['phone'],
                'shop_name' => $row['shop_name'] ?? null,
                'street_address' => $row['address'],
                'area' => $areaId,
                'city' => $cityId,
                'state' => $stateId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        User::insert($data);

        Toastr::success(count($data) . 'customers imported successfully!');
        return redirect()->route('admin.customer.list');
    }


    public function update_customer(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            // 'password' => 'nullable',
        ], [
            'f_name.required' => 'First name is required!',
        ]);

        $user = User::findOrFail($request->id);

        if ($request->password && $request->confirm_password != $request->password) {
            Toastr::error('Password and Confirm Password do not match');
            return redirect()->back();
        }

        $data = [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'area' => $request->area,
            'state' => $request->state,
            // 'shop_name' => $request->shop_name,
            // 'shop_address' => $request->shop_address,
            // 'shop_lat' => $request->shop_lat,
            // 'shop_lng' => $request->shop_lng,
            // 'address' => $request->address,
            'age' => $request->age,
            'gender' => $request->gender,
            'street_address' => $request->address,
        ];

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('image')) {
            $data['image'] = ImageManager::upload('profile/', 'png', $request->file('image'));
        }

      
        $user->update($data);

        Toastr::success('User updated successfully!');

        return back();
    }

    

    public function view(Request $request, $id)
    {

        $customer = User::find($id);
        if (isset($customer)) {


            $query_param = [];
            $search = $request['search'];
            $orders = Order::where(['customer_id' => $id]);
            if ($request->has('search')) {

                $orders = $orders->where('id', 'like', "%{$search}%");
                $query_param = ['search' => $request['search']];
            }
            $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
            return view('admin-views.customer.customer-view', compact('customer', 'orders', 'search'));
        }
        Toastr::error('Customer not found!');
        return back();
    }

    public function delete($id)
    {
        $customer = User::find($id);
        $customer->delete();
        Toastr::success('Customer deleted successfully!');
        return back();
    }

    public function subscriber_list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $subscription_list = Subscription::where('email', 'like', "%{$search}%");

            $query_param = ['search' => $request['search']];
        } else {
            $subscription_list = new Subscription;
        }
        $subscription_list = $subscription_list->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.customer.subscriber-list', compact('subscription_list', 'search'));
    }

    public function customer_settings()
    {
        $data = BusinessSetting::where('type', 'like', 'wallet_%')->orWhere('type', 'like', 'loyalty_point_%')->get();
        $data = array_column($data->toArray(), 'value', 'type');

        return view('admin-views.customer.customer-settings', compact('data'));
    }

    public function customer_update_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(\App\CPU\translate('update_option_is_disable_for_demo'));
            return back();
        }

        $request->validate([
            'add_fund_bonus' => 'nullable|numeric|max:100|min:0',
            'loyalty_point_exchange_rate' => 'nullable|numeric',
        ]);
        BusinessSetting::updateOrInsert(['type' => 'wallet_status'], [
            'value' => $request['customer_wallet'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_status'], [
            'value' => $request['customer_loyalty_point'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['type' => 'wallet_add_refund'], [
            'value' => $request['refund_to_wallet'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_exchange_rate'], [
            'value' => $request['loyalty_point_exchange_rate'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_item_purchase_point'], [
            'value' => $request['item_purchase_point'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_minimum_point'], [
            'value' => $request['minimun_transfer_point'] ?? 0
        ]);

        Toastr::success(\App\CPU\translate('customer_settings_updated_successfully'));
        return back();
    }

    public function get_customers(Request $request)
    {
        $key = explode(' ', $request['q']);
        $data = User::where('id', '!=', 0)->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%")
                    // ->orWhere('l_name', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%");
            }
        })
            ->limit(8)
            ->get([DB::raw('id, CONCAT(f_name, " ", " (", phone ,")") as text')]);
        if ($request->all) $data[] = (object)['id' => false, 'text' => trans('messages.all')];


        return response()->json($data);
    }


    /**
     * Export product list by excel
     * @param Request $request
     * @param $type
     */
    public function export(Request $request)
    {

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $customers = User::with(['orders'])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('f_name', 'like', "%{$value}%")
                            // ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%");
                        // ->orWhere('email', 'like', "%{$value}%");
                    }
                });
        } else {
            $customers = User::with(['orders']);
        }
        $items = $customers->latest()->get();

        return (new FastExcel($items))->download('customer_list.xlsx');
    }

    public function loginReport(Request $request, $type)
    {
        $data = [];
        if ($type == 1) {
            $customers = User::get();
        } else if ($type == 2) {
            $customers = Seller::get();
        } else if ($type == 3) {
            $customers = DeliveryMan::get();
        }
        return view('admin-views.customer.customer-login-report', compact('data', 'customers', 'type'));
    }

    public function loginReportPaginate(Request $request, $type)
    {
        $data = [];

        $orderbyName = isset($request->columns[$request->order[0]['column']]['name']) ? $request->columns[$request->order[0]['column']]['name'] : 'id';
        $orderBy = isset($request->order[0]['dir']) ? $request->order[0]['dir'] : 'desc';

        $reports = LoginReport::where('type', $type)->orderBy($orderbyName, $orderBy)->limit($request->length)->offset($request->start);

        if ($request->input('customer_id') != 'all') {
            $reports = $reports->where('user_id', $request->input('customer_id'));
        }

        if ($request->input('to') != '' && $request->input('from') != '') {
            $reports = $reports->whereBetween('created_at', [$request->input('to'), $request->input('from')]);
        }

        $reports = $reports->get();

        $dt = [];
        foreach ($reports as $key => $report):

            $customer = "";
            if ($report->customer):
                $customer .= "<a class='text-body text-capitalize' href='javascript:void(0)'>";
                $customer .= "<strong class='title-name'> " . $report->customer['f_name'] . " " . $report->customer['l_name'] . "</strong>";
                $customer .= "</a>";
                $customer .= "<a class='d-block title-color' href='tel: " . $report->customer['phone'] . "'>" . $report->customer['phone'] . "</a>";
            else:
                $customer .= "<label class='badge badge-danger fz-12'>" . \App\CPU\translate('invalid_customer_data') . "</label>";
            endif;

            $dt[] = [
                strval($key + 1),
                $customer,
                $report->description,
                date('d M, Y h:i A', strtotime($report->created_at))
            ];
        endforeach;

        $row['data'] = $dt;
        $row['draw'] = $request->input('draw');
        $row['recordsFiltered'] = count($dt);
        $row['recordsTotal'] = LoginReport::where('type', 1)->count();

        return response()->json($row);
    }

    public function loginActiveReport(Request $request, $type)
    {
        $data = [];

        return view('admin-views.customer.customer-active-report', compact('data', 'type'));
    }

    public function loginActiveReportPaginate(Request $request, $type)
    {
        $data = [];

        $orderbyName = isset($request->columns[$request->order[0]['column']]['name']) ? $request->columns[$request->order[0]['column']]['name'] : 'id';
        $orderBy = isset($request->order[0]['dir']) ? $request->order[0]['dir'] : 'desc';

        if ($type == 1) {
            $customers = User::where('is_active', 1)->orderBy($orderbyName, $orderBy)->limit($request->length)->offset($request->start);
        } else if ($type == 2) {
            $customers = Seller::where('status', 'approved')->orderBy($orderbyName, $orderBy)->limit($request->length)->offset($request->start);
        } else if ($type == 3) {
            $customers = DeliveryMan::where('is_active', 1)->orderBy($orderbyName, $orderBy)->limit($request->length)->offset($request->start);
        }

        if ($request->input('to') != "" && $request->input('from') != "") {
            $customers = $customers->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($request->input('from'))), date('Y-m-d 23:59:00', strtotime($request->input('to')))]);
        }

        $customers = $customers->get();

        $dt = [];
        $totalRecords = 0;
        if ($type == 1) {
            $totalRecords = User::where('is_active', 1)->count();
        } else if ($type == 2) {
            $totalRecords = Seller::where('status', 'approved')->count();
        } else if ($type == 3) {
            $totalRecords = DeliveryMan::where('is_active', 1)->count();
        }
        foreach ($customers as $key => $customer):

            $customerText = "";
            if ($customer):
                $customerText .= "<a class='text-body text-capitalize' href='javascript:void(0)'>";
                $customerText .= "<strong class='title-name'> " . $customer['f_name'] . " " . $customer['l_name'] . "</strong>";
                $customerText .= "</a>";
                $customerText .= "<a class='d-block title-color' href='tel: " . $customer['phone'] . "'>" . $customer['phone'] . "</a>";
            else:
                $customerText .= "<label class='badge badge-danger fz-12'>" . \App\CPU\translate('invalid_customer_data') . "</label>";
            endif;

            if ($type == 1) {
                $status = $customer->is_active == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            } else if ($type == 2) {
                $status = $customer->status == 'approved' ? "<span class='badge badge-success'>" . ucwords($customer->status) . "</span>" : "<span class='badge badge-danger'>" . ucwords($customer->status) . "</span>";
            } else if ($type == 3) {
                $status = $customer->is_active == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            }

            $dt[] = [
                strval($key + 1),
                $customerText,
                $status,
                date('d M, Y h:i A', strtotime($customer->created_at))
            ];
        endforeach;

        $row['data'] = $dt;
        $row['draw'] = $request->input('draw');
        $row['recordsFiltered'] = count($dt);
        $row['recordsTotal'] = $totalRecords;

        return response()->json($row);
    }
}
