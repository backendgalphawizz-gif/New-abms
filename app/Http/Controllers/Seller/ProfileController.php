<?php

namespace App\Http\Controllers\Seller;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\SellerWallet;
use App\Model\WalletTransaction;
use App\Model\State;
use App\Model\City;
use App\Model\Area;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use function App\CPU\translate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function view()
    {
        $data = Seller::where('id', auth('seller')->id())->first();
        return view('seller-views.profile.view', compact('data'));
    }

    public function edit($id)
    {
        if (auth('seller')->id() != $id) {
            Toastr::warning(translate('you_can_not_change_others_profile'));
            return back();
        }

        $data = Seller::with('shop')->where('id', auth('seller')->id())->first();
        $shop_banner = Shop::select('banner')->where('seller_id', auth('seller')->id())->first()->banner;
        $states = State::where('country_id', 101)->orderBy('name', 'asc')->get();
        $cities = City::where('state_id', $data->state)->orderBy('name', 'asc')->get();
        $areas = Area::where('city_id', $data->city)->orderBy('name', 'asc')->get();
        // dd($data);
        return view('seller-views.profile.edit', compact('data', 'shop_banner'), [
            'states' => $states,
            'areas'  => $areas,
            'cities' => $cities
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'f_name' => 'required',
            // 'l_name' => 'required',
            'phone' => 'required'
        ], [
            'f_name.required' => 'First name is required!',
            // 'l_name.required' => 'Last name is required!',
            'phone.required' => 'Phone number is required!',
        ]);

        $seller = Seller::find(auth('seller')->id());
        $seller->f_name = $request->f_name;
        $seller->l_name = $request->l_name ?? "";
        $seller->phone = $request->phone;
        $seller->email = $request->email;

        if ($request->image) {
            $seller->image = ImageManager::update('seller/', $seller->image, 'png', $request->file('image'));
        }
        $seller->save();

        Toastr::info('Profile updated successfully!');
        return back();
    }

    public function settings_password_update(Request $request)
    {
        // $request->validate([
        //     'password' => 'required|same:confirm_password|min:8',
        //     'confirm_password' => 'required',
        // ]);

        $seller = Seller::find(auth('seller')->id());
        $seller->password = bcrypt($request['password']);
        $seller->save();
        Toastr::success('Seller password updated successfully!');
        return back();
    }

    public function bank_update(Request $request, $id)
    {
        $bank = Seller::find(auth('seller')->id());

        $bank->bank_name = $request->bank_name;
        $bank->branch = $request->branch;
        $bank->account_type = $request->account_type;
        // $bank->micr_code = $request->micr_code;
        $bank->bank_address = $request->bank_address;
        $bank->account_no = $request->account_no;
        $bank->ifsc_code = $request->ifsc_code;
        $bank->upi_id = $request->upi_id;
        $bank->holder_name = $request->holder_name;

        if ($request->upi_scanner) {
            $bank->upi_scanner = ImageManager::update('seller/', $bank->upi_scanner, 'png', $request->file('upi_scanner'));
        }

        $bank->save();
        Toastr::success('Bank Info updated');
        return back();
    }

    public function bank_edit($id)
    {
        if (auth('seller')->id() != $id) {
            Toastr::warning(translate('you_can_not_change_others_info'));
            return back();
        }
        $data = Seller::where('id', auth('seller')->id())->first();
        return view('seller-views.profile.bankEdit', compact('data'));
    }


    public function update2(Request $request, $id)
    {
        // $request->validate([
        //     'f_name' => 'required',
        //     // 'l_name' => 'required',
        //     'phone' => 'required'
        // ], [
        //     'f_name.required' => 'First name is required!',
        //     // 'l_name.required' => 'Last name is required!',
        //     'phone.required' => 'Phone number is required!',
        // ]);

        $seller = Seller::find(auth('seller')->id());
        // $seller->f_name = $request->f_name;
        // $seller->l_name = $request->l_name ?? "";
        // $seller->phone = $request->phone;
        $seller->minimum_order_amount = $request->minimum_order_amount;

        $seller->save();

        Toastr::info('Quantity updated successfully!');
        return back();
    }

    public function wallet_view(Request $request)
    {
        $seller = Seller::find(auth('seller')->id());
        // dd($seller);
        if (!$seller) {
            return redirect()->route('seller.auth.login')->withErrors(translate('Your existing session token does not authorize you anymore'));
        }


        $seller_wallet = SellerWallet::where('seller_id', $seller->id)->first();
        if (!$seller_wallet) {
            $wallet_amount = 0;
        } else {
            $wallet_amount = $seller_wallet->wallet_amount;
        }
        // dd($wallet_amount);

        $transactions = WalletTransaction::where('user_id', $seller->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('seller-views.wallet.view', compact('wallet_amount', 'transactions', 'seller'));
    }

    public function add_money_wallet(Request $request)
    {
        $request->validate([
            'wallet_amount'   => 'required|numeric|min:0.01',
            'razorpay_payment_id' => 'required|string',
        ]);

        $seller = Seller::find(auth('seller')->id());

        if (!$seller) {
            return redirect()->route('seller.auth.login')->withErrors(translate('Your existing session token does not authorize you anymore'));
        }
        DB::beginTransaction();

        try {
            $seller_wallet = SellerWallet::firstOrCreate(
                ['seller_id' => $seller->id],
                ['wallet_amount' => 0]
            );

            $wallet_amount = $request->wallet_amount;
            $seller_wallet->wallet_amount += $wallet_amount;
            $seller_wallet->save();

            $transaction = new WalletTransaction();
            $transaction->user_id = $seller->id;
            $transaction->transaction_id = $request->razorpay_payment_id;
            $transaction->credit = $wallet_amount;
            $transaction->debit = 0;
            $transaction->admin_bonus = 0;
            $transaction->balance = $seller_wallet->wallet_amount;
            $transaction->transaction_type = 'add_wallet';
            $transaction->reference = "Razorpay";
            $transaction->save();


            DB::commit();

            Toastr::success(translate('Amount added to your wallet successfully.'));
            return redirect()->route('seller.wallet.view');
        } catch (\Exception $e) {
            DB::rollBack();

            Toastr::error(translate('Something went wrong. Please try again later.'));
            return redirect()->back()->withInput();
        }
    }
}
