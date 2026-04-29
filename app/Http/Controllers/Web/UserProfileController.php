<?php

namespace App\Http\Controllers\Web;

use App\CPU\CustomerManager;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\DeliveryCountryCode;
use App\Model\DeliveryMan;
use App\Model\DeliveryZipCode;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\Seller;
use App\Model\ShippingAddress;
use App\Model\SupportTicket;
use App\Model\Wishlist;
use App\Model\RefundRequest;
use App\Model\UserNotification;
use App\Traits\CommonTrait;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use function App\CPU\translate;
use App\CPU\Convert;
use App\Model\Category;
use App\Model\FlashDeal;
use App\Model\ProductCompare;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Plan;
use App\Model\SellerSubscription;
use Illuminate\Support\Facades\Hash;

use function React\Promise\all;

class UserProfileController extends Controller
{
    use CommonTrait;

    public function __construct(
        private Order $order,
        private Seller $seller,
        private Product $product,
        private Category $category,
        private Review $review,
        private DeliveryMan $deliver_man,
        private ProductCompare $compare,
        private Wishlist $wishlist,
    ) {}

    public function user_profile(Request $request)
    {

        // find what you need
        $find_what_you_need_categories = $this->category->where('parent_id', 0)->where('home_status', true)
            ->with(['childes' => function ($query) {
                $query->withCount(['sub_category_product' => function ($query) {
                    return $query->active();
                }]);
            }])
            ->withCount(['product' => function ($query) {
                return $query->active();
            }])
            ->get()->toArray();




        $get_categories = [];
        foreach ($find_what_you_need_categories as $category) {
            $slice = array_slice($category['childes'], 0, 4);
            $category['childes'] = $slice;
            $get_categories[] = $category;
        }

        $final_category = [];
        foreach ($get_categories as $category) {
            if (count($category['childes']) > 0) {
                $final_category[] = $category;
            }
        }
        $flash_dealss = FlashDeal::where('deal_type', 'special-offers')->get();

        $wishlists = $this->wishlist->whereHas('wishlistProduct', function ($q) {
            return $q;
        })->where('customer_id', auth('customer')->id())->count();
        $total_order = $this->order->where('customer_id', auth('customer')->id())->count();
        $total_loyalty_point = auth('customer')->user()->loyalty_point;
        $total_wallet_balance = auth('customer')->user()->wallet_balance;
        $addresses = ShippingAddress::where('customer_id', auth('customer')->id())->get();
        $customer_detail = User::where('id', auth('customer')->id())->first();

        return view(VIEW_FILE_NAMES['user_profile'], compact('final_category', 'flash_dealss', 'customer_detail', 'addresses', 'wishlists', 'total_order', 'total_loyalty_point', 'total_wallet_balance'));
    }

    public function user_account(Request $request)
    {
        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        $customerDetail = User::where('id', auth('customer')->id())->first();
        $countries = Country::get();
        $states = [];
        if ($customerDetail['country'] != "") {
            $states = Country::where('name', $customerDetail['country'])->first()->states;
        }
        $cities = [];
        if ($customerDetail['state'] != "") {
            $cities = State::where('name', $customerDetail['state'])->first()->cities;
        }
        return view(VIEW_FILE_NAMES['user_account'], compact('customerDetail', 'countries', 'states', 'cities'));
    }

    public function user_change_password(Request $request)
    {
        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        $customerDetail = User::where('id', auth('customer')->id())->first();

        $countries = Country::get();
        $states = [];
        if ($customerDetail['country'] != "") {
            $states = Country::where('name', $customerDetail['country'])->first()->states;
        }
        $cities = [];
        if ($customerDetail['state'] != "") {
            $cities = State::where('name', $customerDetail['state'])->first()->cities;
        }
        return view(VIEW_FILE_NAMES['user_change_password'], compact('customerDetail'));
    }

    public function user_update_password(Request $request)
    {


        $request->validate([
            'old_password' => 'required',
            'password' => 'required|same:confirm_password'
        ], [
            'old_password.required' => 'Old password is required',
            'password.required' => 'Password is required'
        ]);

        $user = User::find(auth('customer')->id());
        if (Hash::check($request->input('old_password'), $user->password)) {
            if (Hash::check($request->input('password'), $user->password)) {
                $response = ['status' => false, 'message' => 'Password should not same as old password'];
            } else {

                $user->password = Hash::make($request->input('password'));
                $user->save();
                $response = ['status' => true, 'message' => 'Password updated success'];
            }
        } else {
            $response = ['status' => false, 'message' => 'Old password not matched'];
        }

        return response()->json($response, 200);
    }

    public function user_subscription(Request $request)
    {

        $monthly_plans = Plan::where(['type' => 2, 'user_type' => 1])->get();
        $yearly_plans = Plan::where(['type' => 3, 'user_type' => 1])->get();

        return view(VIEW_FILE_NAMES['user_subscription'], compact('monthly_plans', 'yearly_plans'));
    }

    public function plan_purchased(Request $request)
    {
        $plan = Plan::find($request->input('plan_id'));

        $expiry_date = date('Y-m-d');
        if ($plan->type == 1) { // Day
            $expiry_date = date('Y-m-d', strtotime($expiry_date . ' + ' . $plan->time . ' days'));
        } else if ($plan->type == 2) { // Month
            $expiry_date = date('Y-m-d', strtotime($expiry_date . ' + ' . $plan->time . ' month'));
        } else if ($plan->type == 3) { // Year
            $expiry_date = date('Y-m-d', strtotime($expiry_date . ' + ' . $plan->time . ' year'));
        }

        $subscribe = new SellerSubscription;
        $subscribe->plan_id = $request->input('plan_id');
        $subscribe->transaction_id = $request->input('transaction_id');
        $subscribe->expiry_date = $expiry_date;
        $subscribe->seller_id = auth('customer')->id();
        $subscribe->type = 1;
        $subscribe->save();

        $response = [
            'status' => true,
            'message' => 'User subscription purchased success',
            'data' => []
        ];

        return response()->json($response, 200);
    }

    public function user_update(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            // 'l_name' => 'required',
        ], [
            'f_name.required' => 'Name is required',
            // 'l_name.required' => 'Last name is required',
        ]);

        $image = $request->file('image');

        if ($image != null) {
            $imageName = ImageManager::update('profile/', auth('customer')->user()->image, 'png', $request->file('image'));
        } else {
            $imageName = auth('customer')->user()->image;
        }

        User::where('id', auth('customer')->id())->update([
            'image' => $imageName,
        ]);

        $userDetails = [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name ?? "",
            'gender' => $request->gender ?? "",
            'country' => $request->country ?? "",
            'state' => $request->state ?? "",
            'city' => $request->city ?? "",
            'zip' => $request->pincode ?? ""
        ];
        if (auth('customer')->check()) {
            User::where(['id' => auth('customer')->id()])->update($userDetails);

            return redirect()->back()->with('message', translate('updated_successfully'));
        } else {
            return redirect()->back();
        }
    }

    public function account_address_add()
    {
        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
        $default_location = Helpers::get_business_settings('default_location');

        // $countries = $this->get_delivery_country_array();

        // $zip_codes = $zip_restrict_status ? DeliveryZipCode::all() : 0;
        $countries = $this->get_delivery_country_array();
        $zip_codes = $zip_restrict_status ? DeliveryZipCode::all() : 0;

        return view(VIEW_FILE_NAMES['account_address_add'], compact('countries', 'zip_restrict_status', 'zip_codes', 'default_location'));
    }

    public function account_delete($id)
    {
        if (auth('customer')->id() == $id) {
            $user = User::find($id);
            auth()->guard('customer')->logout();

            ImageManager::delete('/profile/' . $user['image']);
            session()->forget('wish_list');

            $user->delete();
            Toastr::info(translate('Your_account_deleted_successfully!!'));
            return redirect()->route('home');
        } else {
            Toastr::warning('access_denied!!');
        }
    }

    public function account_address()
    {
        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');

        $countries = $this->get_delivery_country_array();
        $zip_codes = $zip_restrict_status ? DeliveryZipCode::all() : 0;

        if (auth('customer')->check()) {
            $shippingAddresses = \App\Model\ShippingAddress::where('customer_id', auth('customer')->id())->get();
            return view('web-views.users-profile.account-address', compact('shippingAddresses', 'country_restrict_status', 'zip_restrict_status', 'countries', 'zip_codes'));
        } else {
            return redirect()->route('home');
        }
    }

    public function address_store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'phone'     => 'required',
            'city'      => 'required',
            // 'zip'       => 'required',
            // 'address'   => 'required',
            // 'country' => 'required', // optional if hardcoded
        ]);

        if (auth('customer')->check()) {
            $address = new ShippingAddress();

            $address->customer_id              = auth('customer')->id();
            $address->contact_person_name  = $request->name;
            $address->address_type         = $request->addressAs ?? 'home';
            $address->address              = $request->address;
            $address->address1             = $request->address_line_1 ?? "";
            $address->city                 = $request->city ?? "";
            $address->state                = $request->state ?? "";
            $address->zip                  = $request->zip ?? "";
            $address->country              = 'India'; 
            $address->phone                = $request->phone ?? "";
            $address->area = $request->area ?? $request->address_line_1 ?? "";
            $address->alt_phone            = $request->alt_phone ?? "";
            $address->is_billing           = $request->is_billing ?? 0;
            $address->latitude             = $request->latitude ?? 0;
            $address->longitude            = $request->longitude ?? 0;
            $address->created_at           = now();

            $address->save();


            Toastr::success(translate('New address added successfully!'));
            $returnUrl = $request->input('return');

            if ($returnUrl) {
                return redirect($returnUrl);
            } else {
                return redirect()->route('user-profile');
            }
        } else {
            Toastr::error(translate('Insufficient_permission!'));
            return redirect()->back();
        }
    }


    // public function address_store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'phone' => 'required',
    //         'city' => 'required',
    //         'zip' => 'required',
    //         // 'country' => 'required',
    //         'address' => 'required',
    //     ]);

    //     $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
    //     $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');

    //     $country_exist = self::delivery_country_exist_check($request->country);
    //     $zipcode_exist = self::delivery_zipcode_exist_check($request->zip);

    //     if ($country_restrict_status && !$country_exist) {
    //         Toastr::error(translate('Delivery_unavailable_in_this_country!'));
    //         return back();
    //     }

    //     if ($zip_restrict_status && !$zipcode_exist) {
    //         Toastr::error(translate('Delivery_unavailable_in_this_zip_code_area!'));
    //         return back();
    //     }

    //     $address = [
    //         'customer_id' => auth('customer')->check() ? auth('customer')->id() : null,
    //         'contact_person_name' => $request->name,
    //         'address_type' => $request->addressAs,
    //         'address' => $request->address,
    //         'address1' => $request->address1 ?? "",
    //         // 'city' => $request->city,
    //         // 'state' => $request->state,
    //         'country' => $request->country,
    //         'state' => $request->state,
    //         'city' => $request->city,
    //         'area' => $request->area,
    //         'zip' => $request->zip,
    //         // 'country' => $request->country,
    //         'phone' => $request->phone,
    //         'alt_phone' => $request->alt_phone,
    //         'is_billing' => $request->is_billing,
    //         'latitude' => $request->latitude,
    //         'longitude' => $request->longitude,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ];
    //     DB::table('shipping_addresses')->insert($address);

    //     Toastr::success(translate('address_added_successfully!'));
    //     return back();
    // }

    public function address_edit(Request $request, $id)
    {
        $shippingAddress = ShippingAddress::where('customer_id', auth('customer')->id())->find($id);
        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');

        $delivery_countries = self::get_delivery_country_array();
        // if ($country_restrict_status) {
        // } else {
        //     $delivery_countries = 0;
        // }
        if ($zip_restrict_status) {
            $delivery_zipcodes = DeliveryZipCode::all();
        } else {
            $delivery_zipcodes = 0;
        }
        if (isset($shippingAddress)) {
            return view(VIEW_FILE_NAMES['account_address_edit'], compact('shippingAddress', 'country_restrict_status', 'zip_restrict_status', 'delivery_countries', 'delivery_zipcodes'));
        } else {
            Toastr::warning(translate('access_denied'));
            return back();
        }
    }

    public function address_update(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'zip' => 'required',
            // 'country' => 'required',
            'address' => 'required',
        ]);

        // $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        // $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');

        // $country_exist = self::delivery_country_exist_check($request->country);
        // $zipcode_exist = self::delivery_zipcode_exist_check($request->zip);

        // if ($country_restrict_status && !$country_exist) {
        //     Toastr::error(translate('Delivery_unavailable_in_this_country!'));
        //     return back();
        // }

        // if ($zip_restrict_status && !$zipcode_exist) {
        //     Toastr::error(translate('Delivery_unavailable_in_this_zip_code_area!'));
        //     return back();
        // }

        if (auth('customer')->check()) {
            $address = ShippingAddress::findOrFail($request->id);

            $address->contact_person_name = $request->name;
            $address->address_type        = $request->addressAs;
            $address->address             = $request->address;
            $address->address1            = $request->address_line_1 ?? "";
            $address->city                = $request->city ?? "";
            $address->area = $request->area ?? $request->address_line_1 ?? "";
            $address->state               = $request->state ?? "";
            $address->zip                 = $request->zip ?? "";
            $address->country             = "India";
            $address->phone               = $request->phone ?? "";
            $address->alt_phone           = $request->alt_phone ?? "";
            $address->is_billing          = $request->is_billing;
            $address->latitude            = $request->latitude;
            $address->longitude           = $request->longitude;
            $address->updated_at          = now(); // Optional, Laravel updates this automatically if timestamps are enabled

            $address->save();

            Toastr::success(translate('Data_updated_successfully!'));
           $returnUrl = $request->input('return');

            if ($returnUrl) {
                return redirect($returnUrl);
            } else {
                return redirect()->route('user-profile');
            }
        } else {
            Toastr::error(translate('Insufficient_permission!'));
            return redirect()->back();
        }
    }

    public function address_delete(Request $request)
    {
        if (auth('customer')->check()) {
            ShippingAddress::destroy($request->id);
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function account_payment()
    {
        if (auth('customer')->check()) {
            return view('web-views.users-profile.account-payment');
        } else {
            return redirect()->route('home');
        }
    }

    public function account_oder(Request $request)
    {

        // find what you need
        $find_what_you_need_categories = $this->category->where('parent_id', 0)->where('home_status', true)
            ->with(['childes' => function ($query) {
                $query->withCount(['sub_category_product' => function ($query) {
                    return $query->active();
                }]);
            }])
            ->withCount(['product' => function ($query) {
                return $query->active();
            }])
            ->get()->toArray();




        $get_categories = [];
        foreach ($find_what_you_need_categories as $category) {
            $slice = array_slice($category['childes'], 0, 4);
            $category['childes'] = $slice;
            $get_categories[] = $category;
        }

        $final_category = [];
        foreach ($get_categories as $category) {
            if (count($category['childes']) > 0) {
                $final_category[] = $category;
            }
        }
        $flash_dealss = FlashDeal::where('deal_type', 'special-offers')->get();
        $statusArr = $request->input('status');

        $order_by = $request->order_by ?? 'desc';
        if (theme_root_path() == 'theme_fashion') {
            $show_order = $request->show_order ?? 'ongoing';

            $array = ['pending', 'confirmed', 'out_for_delivery', 'processing'];
            $orders = $this->order
                ->where(['customer_id' => auth('customer')->id()])
                ->when($show_order == 'ongoing', function ($query) use ($array) {
                    $query->whereIn('order_status', $array);
                })
                ->when($show_order == 'previous', function ($query) use ($array) {
                    $query->whereNotIn('order_status', $array);
                })
                ->when($request['search'], function ($query) use ($request) {
                    $query->where('id', 'like', "%{$request['search']}%");
                })
                ->orderBy('id', $order_by)->paginate(10)->appends(['show_order' => $show_order, 'search' => $request->search]);
        } else {
            $orders = Order::where('customer_id', auth('customer')->id())
                ->when($request->input('status'), function ($query) use ($statusArr) {
                    $query->whereIn('order_status', $statusArr);
                })
                ->orderBy('id', $order_by)
                ->paginate(15);
        }

        if ($request->ajax()) {
            return response()->json(['view' => view(VIEW_FILE_NAMES['ajax_account_orders'], compact('orders'))->render(), 'statusArr' => $statusArr]);
        }

        return view(VIEW_FILE_NAMES['account_orders'], compact('orders', 'order_by', 'final_category', 'flash_dealss'));
    }

    public function account_order_details(Request $request)
    {
        $order = $this->order->with(['details.product', 'delivery_man_review'])->where(['customer_id' => auth('customer')->id()])->find($request->id);
        $refund_day_limit = \App\CPU\Helpers::get_business_settings('refund_day_limit');
        $current_date = \Carbon\Carbon::now();
        if ($order) {
            return view(VIEW_FILE_NAMES['account_order_details'], compact('order', 'refund_day_limit', 'current_date'));
        }

        Toastr::warning('Invalid order');
        return redirect(route('account-oder'));
    }

    public function account_order_details_seller_info(Request $request)
    {
        $order = $this->order->with(['seller.shop'])->find($request->id);
        $product_ids = $this->product->where(['added_by' => $order->seller_is, 'user_id' => $order->seller_id])->pluck('id');
        $rating = $this->review->whereIn('product_id', $product_ids);
        $avg_rating = $rating->avg('rating') ?? 0;
        $rating_percentage = round(($avg_rating * 100) / 5);
        $rating_count = $rating->count();
        $product_count = $this->product->where(['added_by' => $order->seller_is, 'user_id' => $order->seller_id])->active()->count();

        return view(VIEW_FILE_NAMES['seller_info'], compact('avg_rating', 'product_count', 'rating_count', 'order', 'rating_percentage'));
    }

    public function account_order_details_delivery_man_info(Request $request)
    {
        $order = $this->order->with(['delivery_man.rating', 'delivery_man' => function ($query) {
            return $query->withCount('review');
        }])
            ->find($request->id);
        $delivered_count = $this->order->where(['order_status' => 'delivered', 'delivery_man_id' => $order->delivery_man_id, 'delivery_type' => 'self_delivery'])->count();

        return view(VIEW_FILE_NAMES['delivery_man_info'], compact('delivered_count', 'order'));
    }


    public function account_wishlist()
    {
        if (auth('customer')->check()) {

            // find what you need
            $find_what_you_need_categories = $this->category->where('parent_id', 0)->where('home_status', true)
                ->with(['childes' => function ($query) {
                    $query->withCount(['sub_category_product' => function ($query) {
                        return $query->active();
                    }]);
                }])
                ->withCount(['product' => function ($query) {
                    return $query->active();
                }])
                ->get()->toArray();




            $get_categories = [];
            foreach ($find_what_you_need_categories as $category) {
                $slice = array_slice($category['childes'], 0, 4);
                $category['childes'] = $slice;
                $get_categories[] = $category;
            }

            $final_category = [];
            foreach ($get_categories as $category) {
                if (count($category['childes']) > 0) {
                    $final_category[] = $category;
                }
            }

            $wishlists = Wishlist::where('customer_id', auth('customer')->id())->get();
            return view('web-views.products.wishlist', compact('wishlists', 'final_category', 'flash_dealss'));
        } else {
            return redirect()->route('home');
        }
    }

    public function account_tickets()
    {
        if (auth('customer')->check()) {
            $supportTickets = null;
            if (theme_root_path() != 'default') {
                $supportTickets = SupportTicket::where('customer_id', auth('customer')->id())->latest()->paginate(10);
            }
            return view(VIEW_FILE_NAMES['account_tickets'], compact('supportTickets'));
        } else {
            return redirect()->route('home');
        }
    }

    public function ticket_submit(Request $request)
    {
        $ticket = [
            'subject' => $request['ticket_subject'],
            'type' => $request['ticket_type'],
            'customer_id' => auth('customer')->check() ? auth('customer')->id() : null,
            'priority' => $request['ticket_priority'],
            'description' => $request['ticket_description'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('support_tickets')->insert($ticket);
        return back();
    }

    public function single_ticket(Request $request)
    {
        $ticket = SupportTicket::where('id', $request->id)->first();
        return view(VIEW_FILE_NAMES['ticket_view'], compact('ticket'));
    }

    public function comment_submit(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required',
        ], [
            'comment.required' => 'Type something',
        ]);

        DB::table('support_tickets')->where(['id' => $id])->update([
            'status' => 'open',
            'updated_at' => now(),
        ]);

        DB::table('support_ticket_convs')->insert([
            'customer_message' => $request->comment,
            'support_ticket_id' => $id,
            'position' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back();
    }

    public function support_ticket_close($id)
    {
        DB::table('support_tickets')->where(['id' => $id])->update([
            'status' => 'close',
            'updated_at' => now(),
        ]);
        Toastr::success('Ticket closed!');
        return redirect('/account-tickets');
    }

    public function account_transaction()
    {
        $customer_id = auth('customer')->id();
        $customer_type = 'customer';
        if (auth('customer')->check()) {
            $transactionHistory = CustomerManager::user_transactions($customer_id, $customer_type);
            return view('web-views.users-profile.account-transaction', compact('transactionHistory'));
        } else {
            return redirect()->route('home');
        }
    }

    public function support_ticket_delete(Request $request)
    {

        if (auth('customer')->check()) {
            $support = SupportTicket::find($request->id);
            $support->delete();
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function account_wallet_history($user_id, $user_type = 'customer')
    {
        $customer_id = auth('customer')->id();
        if (auth('customer')->check()) {
            $wallerHistory = CustomerManager::user_wallet_histories($customer_id);
            return view('web-views.users-profile.account-wallet', compact('wallerHistory'));
        } else {
            return redirect()->route('home');
        }
    }

    public function track_order()
    {
        return view(VIEW_FILE_NAMES['tracking-page']);
    }
    public function track_order_wise_result(Request $request)
    {
        if (auth('customer')->check()) {
            $orderDetails = Order::where('id', $request['order_id'])->whereHas('details', function ($query) {
                $query->where('customer_id', (auth('customer')->id()));
            })->first();
            return view(VIEW_FILE_NAMES['track_order_wise_result'], compact('orderDetails'));
        }
    }

    public function track_order_result(Request $request)
    {

        $user = auth('customer')->user();
        if (!isset($user)) {
            $user_id = User::where('phone', $request->phone_number)->first()->id;
            $orderDetails = Order::where('id', $request['order_id'])->whereHas('details', function ($query) use ($user_id) {
                $query->where('customer_id', $user_id);
            })->first();
        } else {
            if ($user->phone == $request->phone_number) {
                $orderDetails = Order::where('id', $request['order_id'])->whereHas('details', function ($query) {
                    $query->where('customer_id', auth('customer')->id());
                })->first();
            }
            if ($request->from_order_details == 1) {
                $orderDetails = Order::where('id', $request['order_id'])->whereHas('details', function ($query) {
                    $query->where('customer_id', auth('customer')->id());
                })->first();
            }
        }

        if (isset($orderDetails)) {
            return view(VIEW_FILE_NAMES['track_order'], compact('orderDetails'));
        }

        Toastr::error(translate('Invalid Order Id or Phone Number'));
        return redirect()->route('track-order.index');
    }

    public function track_last_order()
    {
        $orderDetails = OrderManager::track_order(Order::where('customer_id', auth('customer')->id())->latest()->first()->id);

        if ($orderDetails != null) {
            return view('web-views.order.tracking', compact('orderDetails'));
        } else {
            return redirect()->route('track-order.index')->with('Error', \App\CPU\translate('Invalid Order Id or Phone Number'));
        }
    }

    public function order_cancel(Request $request, $id)
    {
        $order = Order::where(['id' => $id])->first();
        if ($order['payment_method'] == 'cash_on_delivery' && $order['order_status'] == 'pending') {
            OrderManager::stock_update_on_order_status_change($order, 'canceled');
            Order::where(['id' => $id])->update([
                'order_status' => 'canceled'
            ]);
            self::add_order_status_history($id, auth('customer')->id(), 'canceled', 'customer', $request->remarks ?? "");
            if ($request->ajax()) {
                if (isset($order['payment_status']) && $order['payment_status'] == 'paid') {
                    CustomerManager::create_wallet_transaction($order->customer_id, Convert::default($order->amount), 'order_refund', 'order_refund');
                }

                return response()->json(['status' => true, 'message' => translate('successfully_canceled')]);
            }
            Toastr::success(translate('successfully_canceled'));
            return back();
        }
        if ($request->ajax()) {
            return response()->json(['status' => false, 'message' => translate('status_not_changable_now')]);
        }
        Toastr::error(translate('status_not_changable_now'));
        return back();
    }

    public function refund_request(Request $request, $id)
    {
        $order_details = OrderDetail::find($id);
        $user = auth('customer')->user();

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        if ($loyalty_point_status == 1) {
            $loyalty_point = CustomerManager::count_loyalty_point_for_amount($id);

            if ($user->loyalty_point < $loyalty_point) {
                Toastr::warning(translate('you have not sufficient loyalty point to refund this order!!'));
                return back();
            }
        }

        return view('web-views.users-profile.refund-request', compact('order_details'));
    }

    public function store_refund(Request $request)
    {
        $request->validate([
            'order_details_id' => 'required',
            'amount' => 'required',
            'refund_reason' => 'required'

        ]);
        $order_details = OrderDetail::find($request->order_details_id);
        $user = auth('customer')->user();


        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        if ($loyalty_point_status == 1) {
            $loyalty_point = CustomerManager::count_loyalty_point_for_amount($request->order_details_id);

            if ($user->loyalty_point < $loyalty_point) {
                Toastr::warning(translate('you have not sufficient loyalty point to refund this order!!'));
                return back();
            }
        }
        $refund_request = new RefundRequest;
        $refund_request->order_details_id = $request->order_details_id;
        $refund_request->customer_id = auth('customer')->id();
        $refund_request->status = 'pending';
        $refund_request->amount = $request->amount;
        $refund_request->product_id = $order_details->product_id;
        $refund_request->order_id = $order_details->order_id;
        $refund_request->refund_reason = $request->refund_reason;

        if ($request->file('images')) {
            foreach ($request->file('images') as $img) {
                $product_images[] = ImageManager::upload('refund/', 'png', $img);
            }
            $refund_request->images = json_encode($product_images);
        }
        $refund_request->save();

        $order_details->refund_request = 1;
        $order_details->save();

        Toastr::success(translate('refund_requested_successful!!'));
        return redirect()->route('account-order-details', ['id' => $order_details->order_id]);
    }

    public function generate_invoice($id)
    {
        $order = Order::with('seller')->with('shipping')->where('id', $id)->first();
        $data["email"] = $order->customer["email"];
        $data["order"] = $order;

        $mpdf_view = \View::make(VIEW_FILE_NAMES['order_invoice'], compact('order'));
        Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);
    }

    public function refund_details($id)
    {
        $order_details = OrderDetail::find($id);
        $refund = RefundRequest::where('customer_id', auth('customer')->id())
            ->where('order_details_id', $order_details->id)->first();
        $product = $this->product->find($order_details->product_id);
        $order = $this->order->find($order_details->order_id);

        if ($product) {
            return view(VIEW_FILE_NAMES['refund_details'], compact('order_details', 'refund', 'product', 'order'));
        }

        Toastr::error(translate('product_not_found'));
        return redirect()->back();
    }

    public function submit_review(Request $request, $id)
    {
        $order_details = OrderDetail::where(['id' => $id])->whereHas('order', function ($q) {
            $q->where(['customer_id' => auth('customer')->id(), 'payment_status' => 'paid']);
        })->first();

        if (!$order_details) {
            Toastr::error(translate('Invalid order!'));
            return redirect('/');
        }

        return view('web-views.users-profile.submit-review', compact('order_details'));
    }

    public function mark_as_read(Request $request)
    {
        UserNotification::where('user_id', auth('customer')->user()->id)->update(['is_read' => 1]);
        $response = ['status' => true, 'message' => 'All Notification marked as read'];
        return response()->json($response, 200);
    }
}
