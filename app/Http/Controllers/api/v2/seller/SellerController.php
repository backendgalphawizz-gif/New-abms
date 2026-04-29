<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\ProductManager;
use App\Http\Controllers\Controller;
use App\Model\DeliveryMan;

use App\Model\Order;
use App\Model\Advertisement;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\Review;
use App\Model\Seller;
use App\Model\SellerWallet;
use App\Model\Shop;
use App\Model\Plan;
use App\Model\WithdrawRequest;
use App\Model\SellerSubscription;
use App\Model\Category;
use App\Model\CategoryRequest;
use App\Model\HelpTopic;
use App\Model\SellerNotification;
use App\Model\SupportTicket;
use App\Model\SupportTicketConv;
use App\Model\PurchaseAds;
use App\Model\WalletTransaction;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function App\CPU\translate;
use Illuminate\Support\Facades\Validator;


class SellerController extends Controller
{
    public function shop_info(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $product_ids = Product::where(['user_id' => $seller['id'], 'added_by' => 'seller'])->pluck('id')->toArray();

            $shop = Seller::find($seller['id']);
            $seller_wallet = SellerWallet::where('seller_id', $seller['id'])->first();
            // dd($seller_wallet);
            // $shop = Shop::select('id','seller_id','name','address','contact','email','image','vacation_status','temporary_close','created_at','updated_at','banner','bussiness_type','registeration_number','gst_in','tax_identification_number','website_link','city','state','pincode','country')->where(['seller_id' => $seller['id']])->first();

            $shop->bank_name = $shop->bank_name ?? "";
            $shop->branch = $shop->branch ?? "";
            $shop->account_no = $shop->account_no ?? "";
            $shop->holder_name = $shop->holder_name ?? "";
            $shop->account_type = $shop->account_type ?? "";
            $shop->micr_code = $shop->micr_code ?? "";
            $shop->bank_address = $shop->bank_address ?? "";
            $shop->ifsc_code = $shop->ifsc_code ?? "";

            $shop->shop->bottom_banner = $shop->bottom_banner ?? "";
            $shop->shop->vacation_start_date = $shop->vacation_start_date ?? "";
            $shop->shop->vacation_end_date = $shop->vacation_end_date ?? "";
            $shop->shop->vacation_note = $shop->vacation_note ?? "";
            $shop->remember_token = $shop->remember_token ?? "";
            $shop->sales_commission_percentage = strval($shop->sales_commission_percentage ?? "");
            $shop->gst = $shop->gst ?? "";
            $shop->cm_firebase_token = $shop->cm_firebase_token ?? "";
            $shop->image = $shop->image == 'def.png' ? asset('storage/app/' . $shop->image) : asset('storage/app/public/seller/' . $shop->image);
            $shop->upi_scanner = $shop->upi_scanner == 'def.png' ? asset('storage/app/' . $shop->upi_scanner) : asset('storage/app/public/seller/' . $shop->upi_scanner);
            $shop->shop->image = $shop->shop->image == 'def.png' ? asset('storage/app/' . $shop->shop->image) : asset('storage/app/public/shop/' . $shop->shop->image);
            // $shop->shop = Helpers::set_shop_data($shop->shop);
            $shop['rating'] = strval(round(Review::whereIn('product_id', $product_ids)->avg('rating'), 2));
            $shop['rating_count'] = strval(Review::whereIn('product_id', $product_ids)->count());
            $shop['seller_wallet'] = $seller_wallet;
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $response = [
            'status' => true,
            'message' => 'Seller profile',
            'data' => [$shop]
        ];

        return response()->json($response, 200);
    }

    public function rating_n_reviews(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $product_ids = Product::where(['user_id' => $seller['id'], 'added_by' => 'seller'])->pluck('id')->toArray();
            $products = Product::where(['user_id' => $seller['id'], 'added_by' => 'seller'])->get();
            $products = Helpers::product_data_formatting($products, true);
            $shop = Seller::find($seller['id']);
            $shop->shop;

            $dt['status'] = false;
            $dt['message'] = "Rating & Reviews";
            $dt['avg_rating'] = number_format(Review::whereIn('product_id', $product_ids)->avg('rating'), 2);
            $dt['rating_count'] = strval(Review::whereIn('product_id', $product_ids)->count());
            $dt['products'] = $products;

            return response()->json($dt, 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }
    }

    public function review_details(Request $request)
    {
        $product_id = $request->input('product_id');

        $product = Product::find($product_id);

        $overallRating = ProductManager::get_overall_rating($product->reviews);

        list($average_rating, $total_reviews) = $overallRating;

        $rating = ProductManager::get_rating($product->reviews);
        $reviews_of_product = Helpers::set_review_data(Review::where('product_id', $product->id)->get());

        $response = ['status' => true, 'message' => 'Product Review details', 'average_rating' => $average_rating, 'total_reviews' => $total_reviews, 'rating' => $rating, 'reviews' => $reviews_of_product];
        return response()->json($response, 200);
    }

    public function referrals(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $shop = Seller::find($seller['id']);
            $shop->shop;
            $shops = Shop::select('id', 'name', 'created_at')->where(['friends_code' => $shop->shop->refferral])->get();
            foreach ($shops as $dt) {
                $dt->created_at = date('d M,Y', strtotime($dt->created_at));
            }

            $wallet = SellerWallet::select(DB::raw('total_earning'))->where('seller_id', $seller['id'])->first();
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $response = [
            'status' => true,
            'message' => 'Refer n Earn',
            'data' => $shops,
            'wallet' => Helpers::set_symbol(BackEndHelper::usd_to_currency($wallet->total_earning))
        ];

        return response()->json($response, 200);
    }

    public function seller_delivery_man(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
            $delivery_men = DeliveryMan::where(['seller_id' => $seller['id']])->get();
            // $delivery_men = DeliveryMan::get();
            foreach ($delivery_men as $key => $men) {
                $men->amount = "0.00";
                $men->image = asset('storage/app/public/delivery-man/' . $men->image);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        return response()->json(['status' => true, 'message' => 'All delivery man', 'data' => $delivery_men], 200);
    }

    public function shop_product_reviews(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $product_ids = Product::where(['user_id' => $seller['id'], 'added_by' => 'seller'])->pluck('id')->toArray();
            $reviews = Review::whereIn('product_id', $product_ids)->with(['product', 'customer'])->get();
            $reviews->map(function ($data) {
                $data['attachment'] = json_decode($data['attachment'], true);
                return $data;
            });
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        return response()->json($reviews, 200);
    }

    public function shop_product_reviews_status(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $reviews = Review::find($request->id);
            $reviews->status = $request->status;
            $reviews->save();
            return response()->json(['message' => translate('status updated successfully!!')], 200);
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }
    }

    public function seller_info(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        return response()->json(Seller::with(['wallet'])->withCount(['product', 'orders'])->where(['id' => $seller['id']])->first(), 200);
    }

    public function shop_info_update(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $old_image = Shop::where(['seller_id' => $seller['id']])->first()->image;
        $image = $request->file('image');
        if ($image != null) {
            $imageName = ImageManager::update('shop/', $old_image, 'png', $request->file('image'));
        } else {
            $imageName = $old_image;
        }

        Shop::where(['seller_id' => $seller['id']])->update([
            'name' => $request['name'],
            'address' => $request['address'],
            'contact' => $request['contact'],
            'image' => $imageName,
            'updated_at' => now()
        ]);

        return response()->json(translate('Shop info updated successfully!'), 200);
    }

    public function seller_info_update(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $old_image = Seller::where(['id' => $seller['id']])->first()->image;
        $image = $request->file('image');
        if ($image != null) {
            $imageName = ImageManager::update('seller/', $old_image, 'png', $request->file('image'));
        } else {
            $imageName = $old_image;
        }

        Seller::where(['id' => $seller['id']])->update([
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'bank_name' => $request['bank_name'],
            'branch' => $request['branch'],
            'account_no' => $request['account_no'],
            'holder_name' => $request['holder_name'],
            'phone' => $request['phone'],
            'password' => $request['password'] != null ? bcrypt($request['password']) : Seller::where(['id' => $seller['id']])->first()->password,
            'image' => $imageName,
            'updated_at' => now()
        ]);

        if ($request['password'] != null) {
            Seller::where(['id' => $seller['id']])->update([
                'auth_token' => Str::random('50')
            ]);
        }

        return response()->json(translate('Info updated successfully!'), 200);
    }

    public function withdraw_request(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }
        if ($seller->account_no == null || $seller->bank_name == null) {
            return response()->json(['message' => translate('Update your bank info first')], 202);
        }

        $wallet = SellerWallet::where('seller_id', $seller['id'])->first();
        if (($wallet->total_earning) >= Convert::usd($request['amount']) && $request['amount'] > 1) {
            DB::table('withdraw_requests')->insert([
                'seller_id' => $seller['id'],
                'amount' => Convert::usd($request['amount']),
                'transaction_note' => null,
                'approved' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $wallet->total_earning -= Convert::usd($request['amount']);
            $wallet->pending_withdraw += Convert::usd($request['amount']);
            $wallet->save();
            return response()->json(['status' => true, 'message' => translate('Withdraw request sent successfully!')], 200);
        }
        return response()->json(['status' => false, 'message' => translate('Invalid withdraw request')], 400);
    }

    public function add_money_wallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wallet_amount' => 'required|numeric|min:0.01',
            'payment_method'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] != 1) {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you anymore')
            ], 401);
        }

        $seller = $data['data'];

        DB::beginTransaction();

        try {
            $seller_wallet = SellerWallet::firstOrCreate(
                ['seller_id' => $seller->id],
                ['wallet_amount' => 0]
            );

            $wallet_amount = $request->wallet_amount;
            $seller_wallet->wallet_amount += $wallet_amount;
            $seller_wallet->save();

            $transaction_id = $request->transaction_id ?? 'txn_' . Str::uuid();

            $transaction = new WalletTransaction();
            $transaction->user_id = $seller->id;
            $transaction->transaction_id = $transaction_id;
            $transaction->credit = $wallet_amount;
            $transaction->debit = 0;
            $transaction->admin_bonus = 0;
            $transaction->balance = $seller_wallet->wallet_amount;
            $transaction->transaction_type = 'add_wallet';
            $transaction->reference = $request->payment_method;
            $transaction->save();


            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => translate('Amount added to your wallet!'),
                'data'    => [
                    'wallet_balance' => $seller_wallet->wallet_amount,
                    'transaction_id' => $transaction_id
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => translate('Something went wrong. Please try again later.'),
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    // public function add_money_wallet(Request $request)
    // {
    //     $data = Helpers::get_seller_by_token($request);

    //     if ($data['success'] != 1) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => translate('Your existing session token does not authorize you anymore')
    //         ], 401);
    //     }

    //     if ($request->wallet_amount <= 0) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => translate('Amount can not be be lesst than 1!')
    //         ], 200);
    //     }

    //     $seller = $data['data'];

    //     $seller_wallet = SellerWallet::firstOrCreate(
    //         ['seller_id' => $seller->id],
    //         ['wallet_amount' => 0]
    //     );

    //     $seller_wallet->wallet_amount += $request->wallet_amount;
    //     $seller_wallet->save();

    //     return response()->json([
    //         'status' => true,
    //         'message' => translate('Amount added to your wallet!')
    //     ], 200);
    // }


    public function close_withdraw_request(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $withdraw_request = WithdrawRequest::find($request['id']);
        $wallet = SellerWallet::where('seller_id', $seller['id'])->first();

        if (isset($withdraw_request) && $withdraw_request->approved == 0) {
            $wallet->total_earning += BackEndHelper::currency_to_usd($withdraw_request['amount']);
            $wallet->pending_withdraw -= BackEndHelper::currency_to_usd($request['amount']);
            $wallet->save();
            $withdraw_request->delete();
            return response()->json(translate('Withdraw request has been closed successfully!'), 200);
        }

        return response()->json(translate('Withdraw request is invalid'), 400);
    }

    public function transaction(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'withdrawal_amount' => '0',
                'data' => []
            ], 401);
        }

        $withdrawalAmount = $seller->wallet->total_earning ?? 0;

        $transactions = WithdrawRequest::where('seller_id', $seller['id'])->latest()->get();
        foreach ($transactions as $key => $transaction) {
            $transaction->delivery_man_id = $transaction->delivery_man_id ?? 0;
            $transaction->admin_id = $transaction->admin_id ?? 0;
            $transaction->amount = Helpers::set_symbol(BackEndHelper::usd_to_currency($transaction->amount ?? 0));
            $transaction->withdrawal_method_id = strval($transaction->withdrawal_method_id ?? 0);
            $transaction->withdrawal_method_fields = $transaction->withdrawal_method_fields ?? [];
            $transaction->transaction_note = $transaction->transaction_note ?? "";
        }

        return response()->json(['status' => true, 'message' => 'All Withdrawl Transactions', 'withdrawal_amount' => Helpers::set_symbol(BackEndHelper::usd_to_currency($withdrawalAmount)), 'data' => $transactions], 200);
    }

    public function wallet_transaction(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] != 1) {
            return response()->json([
                'status'  => false,
                'message' => translate('Your existing session token does not authorize you anymore'),
                'data'    => []
            ], 401);
        }

        $seller = $data['data'];

        $seller_wallet = SellerWallet::where('seller_id', $seller->id)->first();

        $transactions = WalletTransaction::where('user_id', $seller->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => translate('Wallet transactions fetched successfully'),
            'wallet_amount' => $seller_wallet->wallet_amount,
            'data'    => $transactions
        ], 200);
    }


    public function monthly_earning(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');
        $seller_data = '';
        $seller_earnings = OrderTransaction::where([
            'seller_is' => 'seller',
            'seller_id' => $seller['id'],
            'status' => 'disburse'
        ])->select(
            DB::raw('IFNULL(sum(seller_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; $inc++) {
            $default = 0;
            foreach ($seller_earnings as $match) {
                if ($match['month'] == $inc) {
                    $default = $match['sums'];
                }
            }
            $seller_data .= $default . ',';
        }

        return response()->json($seller_data, 200);
    }

    public function monthly_commission_given(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $commission_data = '';
        $commission_earnings = OrderTransaction::where([
            'seller_is' => 'seller',
            'seller_id' => $seller['id'],
            'status' => 'disburse'
        ])->select(
            DB::raw('IFNULL(sum(admin_commission),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; $inc++) {
            $default = 0;
            foreach ($commission_earnings as $match) {
                if ($match['month'] == $inc) {
                    $default = $match['sums'];
                }
            }
            $commission_data .= $default . ',';
        }

        return response()->json($commission_data, 200);
    }

    public function update_cm_firebase_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        DB::table('sellers')->where('id', $seller->id)->update([
            'cm_firebase_token' => $request['cm_firebase_token'],
        ]);

        return response()->json(['message' => translate('successfully updated!')], 200);
    }

    public function account_delete(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        if ($seller->id) {
            ImageManager::delete('/seller/' . $seller['image']);

            $seller->delete();
            return response()->json(['status' => true, 'message' => translate('Your_account_deleted_successfully!!')], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'access_denied!!'], 403);
        }
    }

    public function purchase_plan(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

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
        $subscribe->seller_id = $seller['id'];
        $subscribe->type = 2;
        $subscribe->save();

        $response = [
            'status' => true,
            'message' => 'Seller subscription purchased success',
            'data' => []
        ];

        return response()->json($response, 200);
    }

    public function dashboard(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }
        $most_rated_products = Product::where(['user_id' => $seller['id'], 'added_by' => 'seller'])
            ->rightJoin('reviews', 'reviews.product_id', '=', 'products.id')
            ->select([
                DB::raw('AVG(reviews.rating) as ratings_average'),
                DB::raw('count(*) as total')
            ])
            ->orderBy('total', 'desc')
            ->take(6)
            ->first();

        $dateType = $request->type ?? 'yearEarn';

        $seller_data = array();
        if ($dateType == 'yearEarn') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $seller_earnings = OrderTransaction::where([
                'seller_is' => 'seller',
                'seller_id' => $seller['id'],
                'status' => 'disburse'
            ])->select(
                DB::raw('IFNULL(sum(seller_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $seller_data[$inc] = strval(0);
                foreach ($seller_earnings as $match) {
                    if ($match['month'] == $inc) {
                        $seller_data[$inc] = strval($match['sums']);
                    }
                }
            }
            $key_range = array("Jan", "Feb", "Mar", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        } elseif ($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $key_range = range(1, strval($number));

            foreach ($key_range as $kkey => $value) {
                $key_range[$kkey] = strval($value);
            }

            $seller_earnings = OrderTransaction::where([
                'seller_is' => 'seller',
                'seller_id' => $seller['id'],
                'status' => 'disburse'
            ])->select(
                DB::raw('seller_amount'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->whereBetween('created_at', [$from, $to])->groupby('day')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $seller_data[$inc] = strval(0);
                foreach ($seller_earnings as $match) {
                    if ($match['day'] == $inc) {
                        $seller_data[$inc] = strval($match['seller_amount']);
                    }
                }
            }
        } elseif ($dateType == 'WeekEarn') {

            $from = Carbon::now()->startOfWeek()->format('Y-m-d');
            $to = Carbon::now()->endOfWeek()->format('Y-m-d');

            $number_start = date('d', strtotime($from));
            $number_end = date('d', strtotime($to));

            $seller_earnings = OrderTransaction::where([
                'seller_is' => 'seller',
                'seller_id' => $seller['id'],
                'status' => 'disburse'
            ])->select(
                DB::raw('seller_amount'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->whereBetween('created_at', [$from, $to])->get()->toArray();

            for ($inc = $number_start; $inc <= $number_end; $inc++) {
                $seller_data[$inc] = strval(0);
                foreach ($seller_earnings as $match) {
                    if ($match['day'] == $inc) {
                        $seller_data[$inc] = strval($match['seller_amount']);
                    }
                }
            }

            $key_range = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        }

        $seller_label = $key_range;

        $seller_data_final = $seller_data;

        $commission_data = array();
        if ($dateType == 'yearEarn') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $commission_earnings = OrderTransaction::where([
                'seller_is' => 'seller',
                'seller_id' => $seller['id'],
                'status' => 'disburse'
            ])->select(
                DB::raw('IFNULL(sum(admin_commission),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $commission_data[$inc] = "0";
                foreach ($commission_earnings as $match) {
                    if ($match['month'] == $inc) {
                        $commission_data[$inc] = $match['sums'];
                    }
                }
            }

            $key_range = array("Jan", "Feb", "Mar", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        } elseif ($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $key_range = range(strval(1), strval($number));

            $commission_earnings = OrderTransaction::where([
                'seller_is' => 'seller',
                'seller_id' => $seller['id'],
                'status' => 'disburse'
            ])->select(
                DB::raw('admin_commission'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->whereBetween('created_at', [$from, $to])->groupby('day')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $commission_data[$inc] = "0";
                foreach ($commission_earnings as $match) {
                    if ($match['day'] == $inc) {
                        $commission_data[$inc] = $match['admin_commission'];
                    }
                }
            }
        } elseif ($dateType == 'WeekEarn') {

            $from = Carbon::now()->startOfWeek()->format('Y-m-d');
            $to = Carbon::now()->endOfWeek()->format('Y-m-d');

            $number_start = date('d', strtotime($from));
            $number_end = date('d', strtotime($to));

            $commission_earnings = OrderTransaction::where([
                'seller_is' => 'seller',
                'seller_id' => $seller['id'],
                'status' => 'disburse'
            ])->select(
                DB::raw('admin_commission'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->whereBetween('created_at', [$from, $to])->get()->toArray();

            for ($inc = $number_start; $inc <= $number_end; $inc++) {
                $commission_data[$inc] = strval(0);
                foreach ($commission_earnings as $match) {
                    if ($match['day'] == $inc) {
                        $commission_data[$inc] = $match['admin_commission'];
                    }
                }
            }
            $key_range = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        }

        $commission_label = $key_range;

        $commission_data_final = $commission_data;

        $data = array(
            'seller_label' => $seller_label,
            'seller_earn' => array_values($seller_data_final),
            // 'commission_label' => $commission_label,
            // 'commission_earn' => array_values($commission_data_final)
        );

        $categories = Product::where(['added_by' => 'seller', 'user_id' => $seller['id']])->get()->pluck('category_id');

        $selected_category = Category::withCount('product')->whereIn('id', $categories)->get();

        $categoryLabel = [];
        $categoryProductCount = [];
        foreach ($selected_category as $key => $value) {
            $categoryLabel[] = $value['name'];
            $categoryProductCount[] = strval($value['product_count']);
        }

        $category_product = [
            'category_label' => $categoryLabel,
            'category_products' => $categoryProductCount
        ];

        $totalSale = Order::select([DB::raw('SUM(order_amount) as total_amount')])->where(['payment_status' => 'paid', 'order_status' => 'delivered', 'seller_id' => $seller['id'], 'seller_is' => 'seller'])->first();

        $ids = Order::select(DB::raw('DISTINCT customer_id'))->where(['seller_id' => $seller['id'], 'seller_is' => 'seller'])->get()->pluck('customer_id');

        $users = User::select('id', 'f_name', 'email', 'phone', 'street_address', 'country', 'state', 'city', 'zip', 'image')->whereIn('id', $ids)->count();

        // Assuming $seller is already defined
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();

        $todayOrders = Order::where([
            'seller_id' => $seller['id'],
            'seller_is' => 'seller', // Replace with the correct column name for seller role
        ])
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();

        // Assuming $seller is already defined
        $currentMonth = Carbon::now()->startOfMonth();
        $monthOrders = Order::where([
            'seller_id' => $seller['id'],
            'seller_is' => 'seller', // Assuming the correct column name is 'seller_role'
        ])
            ->where('created_at', '>=', $currentMonth)
            ->count();

        // Assuming $seller is already defined
        $currentWeekStart = Carbon::now()->startOfWeek();
        $currentWeekEnd = Carbon::now()->endOfWeek();

        $weekOrderCounts = Order::selectRaw('WEEK(created_at) as week_number')
            ->where([
                'seller_id' => $seller['id'],
                'seller_is' => 'seller', // Replace with the correct column name for seller role
            ])
            ->whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])
            ->count();

        $notifications = SellerNotification::where('user_id', $seller['id'])->where('is_read', 0)->count();

        // addition 
        // Total Sales (completed orders only)
        $total_sales = Order::where([
            'seller_id'   => $seller['id'],
            'seller_is'   => 'seller',
            'payment_status' => 'paid',
            'order_status'   => 'delivered'
        ])->sum('order_amount');

        // Day Earn
        $day_total = OrderTransaction::where([
            'seller_id'   => $seller['id'],
            'seller_is'   => 'seller',
            'status'      => 'disburse'
        ])->whereDate('created_at', Carbon::today())->sum('seller_amount');

        $day_total =    Helpers::set_symbol(BackEndHelper::usd_to_currency($day_total));


        // Week Earn
        $week_total = OrderTransaction::where([
            'seller_id'   => $seller['id'],
            'seller_is'   => 'seller',
            'status'      => 'disburse'
        ])->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('seller_amount');

        $week_total =    Helpers::set_symbol(BackEndHelper::usd_to_currency($week_total));


        // Month Earn
        $month_total = OrderTransaction::where([
            'seller_id'   => $seller['id'],
            'seller_is'   => 'seller',
            'status'      => 'disburse'
        ])->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('seller_amount');

        $month_total =    Helpers::set_symbol(BackEndHelper::usd_to_currency($month_total));


        // dd($seller['id']);
        // Year Earn
        $year_total = OrderTransaction::where([
            'seller_id'   => $seller['id'],
            'seller_is'   => 'seller',
            'status'      => 'disburse'
        ])->whereYear('created_at', Carbon::now()->year)
            ->sum('seller_amount');

        $year_total =    Helpers::set_symbol(BackEndHelper::usd_to_currency($year_total));

        $response = [
            'status' => true,
            'message' => 'Seller Dashboard',
            'data' => [
                'total_sale' => Helpers::set_symbol(BackEndHelper::usd_to_currency($seller->wallet->total_earning ?? 0)),
                'sold_out' => strval(Order::where(['payment_status' => 'paid', 'order_status' => 'delivered', 'seller_id' => $seller['id'], 'seller_is' => 'seller'])->count() ?? 0),
                'total_product' => strval(Product::where(['user_id' => $seller['id'], 'added_by' => 'seller'])->count() ?? 0),
                'total_orders' => strval(Order::where(['seller_id' => $seller['id'], 'seller_is' => 'seller'])->count() ?? 0),
                'total_customers' => strval($users ?? 0),
                'stock_management' => strval(Product::where('current_stock', '<', 4)->where(['user_id' => $seller['id'], 'added_by' => 'seller'])->count() ?? 0),
                'total_delivery' => strval(Order::where(['payment_status' => 'paid', 'order_status' => 'delivered', 'seller_id' => $seller['id'], 'seller_is' => 'seller'])->count()),
                'ratings_nd_reviews' => number_format($most_rated_products->ratings_average ?? 0, 2),
                'graph_data' => $data,
                'category_product' => [$category_product],
                'today' => strval($todayOrders ?? 0),
                'week' => strval($weekOrderCounts ?? 0),
                'month' => strval($monthOrders ?? 0),
                'notification' => strval($notifications ?? 0),
                // 'total_sales' => strval($total_sales ?? 0),
                'day_earn'    => strval($day_total ?? 0),
                'week_earn'   => strval($week_total ?? 0),
                'month_earn'  => strval($month_total ?? 0),
                'year_earn'   => strval($year_total ?? 0),
            ]
        ];

        return response()->json($response);
    }

    public function updateStoreDetails(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $shop = Shop::where('seller_id', $seller['id'])->first();
        $shop->name = $request->company_name;
        $shop->email = $request->bussiness_email_id;
        $shop->bussiness_type = $request->bussiness_type;
        $shop->gst_in = $request->gst_in;
        $shop->registeration_number = $request->bussiness_registeration_number;
        $shop->tax_identification_number = $request->tax_identification_number;

        $shop->website_link = $request->website_link ?? "";
        $shop->social_link = $request->social_link ?? "";
        $shop->details = $request->details ?? "";
        $old_image = Shop::where(['seller_id' => $seller['id']])->first()->image;
        $image = $request->file('image');
        if ($image != null) {
            $imageName = ImageManager::update('shop/', $old_image, 'png', $request->file('image'));
        } else {
            $imageName = $old_image;
        }
        $shop->image = $imageName;
        $shop->save();

        $response = ['status' => true, 'message' => 'Store Profile updated success', 'data' => []];
        return response()->json($response);
    }

    public function updateBussinessDetails(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $shop = Shop::where('seller_id', $seller['id'])->first();
        $shop->name = $request->company_name;
        $shop->email = $request->bussiness_email_id;
        $shop->bussiness_type = $request->bussiness_type;
        $shop->gst_in = $request->gst_in;
        $shop->registeration_number = $request->bussiness_registeration_number;
        $shop->tax_identification_number = $request->tax_identification_number;
        $shop->save();

        $response = ['status' => true, 'message' => 'Bussiness profile updated success', 'data' => []];
        return response()->json($response);
    }

    public function updateAddressDetails(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $shop = Shop::where('seller_id', $seller['id'])->first();
        $shop->address = $request->address;
        $shop->city = $request->city;
        $shop->state = $request->state;
        $shop->pincode = $request->pincode;
        $shop->country = $request->country;
        $shop->save();

        $response = ['status' => true, 'message' => 'Address updated success', 'data' => []];
        return response()->json($response);
    }

    public function updateBankDetails(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $seller = Seller::find($seller['id']);
        // dd($seller);
        $seller->holder_name = $request->holder_name ?? '';
        $seller->bank_name = $request->bank_name;
        $seller->branch = $request->branch_name;
        $seller->account_type = $request->account_type;
        $seller->micr_code = $request->micr_code;
        $seller->bank_address = $request->bank_address;
        $seller->account_no = $request->account_number;
        $seller->ifsc_code = $request->ifsc_code;
        $seller->upi_id = $request->upi_id;

        if ($request->upi_scanner) {
            $seller->upi_scanner = ImageManager::update('seller/', $seller->upi_scanner, 'png', $request->file('upi_scanner'));
        }


        $seller->save();

        $response = ['status' => true, 'message' => 'Bank details updated success', 'data' => []];
        return response()->json($response);
    }

    public function updateProfile(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $shop = Seller::find($seller['id']);
        $shop->f_name = $request->name;
        $old_image = Seller::where(['id' => $seller['id']])->first()->image;
        $image = $request->file('image');
        if ($image != null) {
            $imageName = ImageManager::update('seller/', $old_image, 'png', $request->file('image'));
        } else {
            $imageName = $old_image;
        }
        // $shop->email = $request->email;
        $shop->image = $imageName;
        $shop->minimum_order_amount = $request['minimum_order_amount'];
        $shop->save();

        $shop_info = Shop::where('seller_id', $seller['id'])->first();

        $shop_info->country = 'India';
        $shop_info->state = $request->state;
        $shop_info->city = $request->city;
        $shop_info->area = $request->area;

        $shop_info->save();

        $response = ['status' => true, 'message' => 'Seller profile updated success', 'data' => []];
        return response()->json($response);
    }

    public function updatePassword(Request $request)
    {

        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $sellerArr = [
            'old_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];

        $validator = Validator::make($request->all(), $sellerArr);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields.",
                'token' => "",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }
        $seller = Seller::find($seller['id']);
        $seller->password = bcrypt($request->password);
        $seller->save();

        $response = ['status' => true, 'message' => 'Password updated success', 'data' => [], 'errors' => []];
        return response()->json($response, 200);
    }

    public function categories(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $categoryIds = Product::where('user_id', $seller->id)->get()->pluck('category_id')->toArray();

        // dd($categoryIds);
        // dd($seller);
        // $parentId = $request->post('parent_id') ?? 0;
        // $list = Category::where('parent_id', $parentId)->get();
        $list = Category::whereIn('id', $categoryIds)->get();
        return response()->json(['status' => true, 'message' => 'All category Lists', 'data' => $list]);
    }

    public function static_pages(Request $request)
    {
        $setting = [
            'about_us' => Helpers::get_business_settings('about_us'),
            'privacy_policy' => Helpers::get_business_settings('vendor-privacy-policy'),
            'faq' => HelpTopic::all(),
            'terms_n_conditions' => Helpers::get_business_settings('vendor-terms-policy'),
            'refund_policy' => Helpers::get_business_settings('vendor-refund-policy'),
            'return_policy' => Helpers::get_business_settings('vendor-return-policy'),
            'cancellation_policy' => Helpers::get_business_settings('vendor-cancellation-policy'),

            'shipping_delivery_policy' => Helpers::get_business_settings('vendor-shipping-policy'),
            'security_policy_policy' => Helpers::get_business_settings('vendor-security-policy-policy'),
            'payment_policy' => Helpers::get_business_settings('vendor-payment-policy'),
            // 'condition_of_use_policy' => Helpers::get_business_settings('vendor-condition-of-use-policy'),
            // 'security_information' => Helpers::get_business_settings('vendor-security-information'),
            'contact_us' => Helpers::get_business_settings('contact-us')

        ];
        $response = ['status' => true, 'message' => 'Home Page Items', 'data' => $setting];

        return response()->json($response);
    }

    public function advertisements(Request $request)
    {
        $advertisements = Advertisement::get();
        if (!empty($advertisements)) {
            foreach ($advertisements as $key => $advertisement) {
                $advertisement->amount_with_currency = Helpers::set_symbol(BackEndHelper::usd_to_currency($advertisement->amount));
                $advertisement->amount = strval(BackEndHelper::usd_to_currency($advertisement->amount));
            }
        }

        $response = ['status' => true, 'message' => 'Advertisements List', 'data' => $advertisements];
        return response()->json($response);
    }

    public function purchase_advertisement(Request $request)
    {

        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $adId = $request->input('ad_id');
        $start_date = $request->input('start_date');
        $product_ids = implode(',', $request->input('product_ids'));
        $transactionId = $request->input('transaction_id');

        $image = $request->file('image');
        if ($image != null) {
            $image = ImageManager::upload('ads/', 'png', $request->file('image'));
        }

        $ads = new PurchaseAds;
        $ads->ad_id = $request->input('ad_id');
        $ads->start_date = $request->input('start_date');
        $ads->product_ids = implode(',', $request->input('product_ids'));
        $ads->image = $image;
        $ads->transaction_id = $request->input('transaction_id');
        $ads->amount = $request->input('amount');
        $ads->seller_id = $seller['id'];
        $ads->save();

        $response = ['status' => true, 'message' => 'Advertisement Purchased Success', 'data' => []];
        return response()->json($response);
    }

    public function get_advertisements(Request $request)
    {

        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $ads = PurchaseAds::where('seller_id', $seller['id'])->get();

        foreach ($ads as $key => $ad) {
            $ad->advertisement;
            $ad->image = asset('storage/app/public/ads/' . $ad->image);
        }

        $response = ['status' => true, 'message' => 'Advertisement Purchased Success', 'data' => $ads];
        return response()->json($response);
    }

    public function get_support_tickets(Request $request)
    {

        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $lists = SupportTicket::where('vendor_id', $seller['id'])->get();

        $lists = Helpers::set_support_model($lists);

        $response = ['status' => true, 'message' => 'Support Tickets', 'data' => $lists];

        return response()->json($response, 200);
    }

    public function get_support_ticket_conv($ticket_id)
    {

        $lists = SupportTicketConv::where('support_ticket_id', $ticket_id)->get();

        $lists = Helpers::set_support_conv_model($lists);

        $response = ['status' => true, 'message' => 'Support Tickets', 'data' => $lists];

        return response()->json($response, 200);
    }

    public function reply_support_ticket(Request $request, $ticket_id)
    {
        $support = new SupportTicketConv();
        $support->support_ticket_id = $ticket_id;
        $support->admin_id = 0;
        $support->vendor_message = $request['message'];
        $support->save();

        $response = ['status' => true, 'message' => 'Support ticket reply sent.', 'data' => []];

        return response()->json($response, 200);
    }

    public function request_category(Request $request)
    {

        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $category_name = $request->input('category_name');
        $sub_category_name = $request->input('sub_category_name');
        $sub_sub_category_name = $request->input('sub_sub_category_name');

        $req = new CategoryRequest;
        $req->category_name = $category_name;
        $req->sub_category_name = $sub_category_name ?? "";
        $req->sub_sub_category_name = $sub_sub_category_name ?? "";
        $req->seller_id = $seller['id'];
        $req->save();

        return response()->json([
            'status' => true,
            'message' => translate('Category Requested success'),
            'data' => []
        ], 200);
    }

    public function all_notifications(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        SellerNotification::where('user_id', $seller['id'])->update(['is_read' => 1]);

        $notifications = SellerNotification::where('user_id', $seller['id'])->get();
        return response()->json([
            'status' => true,
            'message' => 'All Seller Notifications',
            'data' => $notifications
        ], 200);
    }

    public function orders_report(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        // Assuming $seller is already defined
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();

        $todayOrders = Order::where([
            'seller_id' => $seller['id'],
            'seller_is' => 'seller', // Replace with the correct column name for seller role
        ])
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();

        // Assuming $seller is already defined
        $currentMonth = Carbon::now()->startOfMonth();
        $monthOrders = Order::where([
            'seller_id' => $seller['id'],
            'seller_is' => 'seller', // Assuming the correct column name is 'seller_role'
        ])
            ->where('created_at', '>=', $currentMonth)
            ->count();

        // Assuming $seller is already defined
        $currentWeekStart = Carbon::now()->startOfWeek();
        $currentWeekEnd = Carbon::now()->endOfWeek();

        $weekOrderCounts = Order::selectRaw('WEEK(created_at) as week_number')
            ->where([
                'seller_id' => $seller['id'],
                'seller_is' => 'seller', // Replace with the correct column name for seller role
            ])
            ->whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])
            ->count();

        $allStatusData = [
            ['value' => 'pending', 'title' => \App\CPU\translate('Pending'), 'count' => strval(0)],
            ['value' => 'confirmed', 'title' => \App\CPU\translate('Confirmed'), 'count' => strval(0)],
            // ['value' => 'processing', 'title' => \App\CPU\translate('Packaging'), 'count' => strval(0)],
            ['value' => 'shipped', 'title' => \App\CPU\translate('Shipped'), 'count' => strval(0)],
            ['value' => 'out_for_delivery', 'title' => \App\CPU\translate('out_for_delivery'), 'count' => strval(0)],
            ['value' => 'delivered', 'title' => \App\CPU\translate('Delivered'), 'count' => strval(0)],
            ['value' => 'returned', 'title' => \App\CPU\translate('Returned'), 'count' => strval(0)],
            ['value' => 'failed', 'title' => \App\CPU\translate('Failed_to_Deliver'), 'count' => strval(0)],
            ['value' => 'canceled', 'title' => \App\CPU\translate('Canceled'), 'count' => strval(0)],
        ];

        foreach ($allStatusData as $key => $dt) {
            $allStatusData[$key]['count'] = strval(Order::where('order_status', $dt['value'])->where([
                'seller_id' => $seller['id'],
                'seller_is' => 'seller', // Replace with the correct column name for seller role
            ])->count() ?? 0);
        }

        $response = [
            'status' => true,
            'message' => 'Order Report',
            'all_status' => $allStatusData,
            'data' => [
                'today' => strval($todayOrders ?? 0),
                'week' => strval($weekOrderCounts ?? 0),
                'month' => strval($monthOrders ?? 0)
            ]
        ];

        return response()->json($response, 200);
    }
}
