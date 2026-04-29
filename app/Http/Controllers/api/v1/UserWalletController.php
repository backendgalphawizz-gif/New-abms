<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\BackEndHelper;
use App\CPU\CustomerManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WalletTransaction;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class UserWalletController extends Controller
{
    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');

        if($wallet_status == 1)
        {
            $user = $request->user();
            $total_wallet_balance = $user->wallet_balance;
            $wallet_transactio_list = WalletTransaction::where('user_id',$user->id)
                                                    ->latest()
                                                    ->paginate($request['limit'], ['*'], 'page', $request['offset']);
        
            $transactions = $wallet_transactio_list->items();
            foreach ($transactions as $key => $transaction) {
                $transaction->credit = Helpers::set_symbol(BackEndHelper::usd_to_currency($transaction->credit));
                $transaction->debit = Helpers::set_symbol(BackEndHelper::usd_to_currency($transaction->debit));
                $transaction->balance = Helpers::set_symbol(BackEndHelper::usd_to_currency($transaction->balance));
            }

            return response()->json([
                'status' => true,
                'message' => 'Wallet Transactions',
                'limit'=>(integer)$request->limit,
                'offset'=>(integer)$request->offset,
                'total_wallet_balance'=> Helpers::set_symbol(BackEndHelper::usd_to_currency($total_wallet_balance)),
                'total_wallet_transactio' => $wallet_transactio_list->total(),
                'wallet_transactio_list' => $transactions
            ],200);
            
        }else{
            
            return response()->json([
                'status' => false,
                'message' => translate('access_denied'),
                'limit'=> 0,
                'offset'=> 0,
                'total_wallet_balance'=> "0.00",
                'total_wallet_transactio' => 0,
                'wallet_transactio_list' => []
            ], 422);
        }
    }

    public function add_wallet(Request $request)
    {
        $user_id = $request->input('user_id');
        $amount = $request->input('amount');
        $transaction_type = $request->input('transaction_type');
        $referance = $request->input('transaction_id');

        $response = CustomerManager::create_wallet_transaction($user_id, $amount, $transaction_type, $referance);
        if($response) {

        }

        return response()->json(['status' => true, 'message' => 'Wallet added success']);
    }
}
