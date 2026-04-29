<?php

namespace App\Http\Controllers\Web;

use App\CPU\CustomerManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WalletTransaction;
use App\CPU\Helpers;
use App\Model\OrderTransaction;
use Brian2694\Toastr\Facades\Toastr;

class UserWalletController extends Controller
{

    public function index()
    {
        $wallet_status = Helpers::get_business_settings('wallet_status');
        if($wallet_status == 1)
        {
            $total_wallet_balance = auth('customer')->user()->wallet_balance;
            $wallet_transactio_list = WalletTransaction::where('user_id',auth('customer')->id())
                                                        ->latest()
                                                        ->paginate(15);

            return view(VIEW_FILE_NAMES['user_wallet'],compact('total_wallet_balance','wallet_transactio_list'));
        }else{
            Toastr::warning(\App\CPU\translate('access_denied!'));
            return back();
        }
    }

    public function my_wallet_account(){
        return view(VIEW_FILE_NAMES['wallet_account']);
    }

    public function add_wallet(Request $request) {
        $user_id = auth('customer')->id();
        $amount = $request->input('amount');
        $transaction_type = 'add_wallet';
        $referance = $request->input('transaction_id');

        CustomerManager::create_wallet_transaction($user_id, $amount, $transaction_type, $referance);

        return response()->json(['status' => true, 'message' => 'Wallet added success']);
    }

    public function transactions() {
        $wallet_transactio_list = OrderTransaction::where('customer_id',auth('customer')->id())->latest()->paginate(15);
        return view(VIEW_FILE_NAMES['user_transactions'],compact('wallet_transactio_list'));
    }

}
