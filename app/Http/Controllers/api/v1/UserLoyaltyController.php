<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;
use App\Model\LoyaltyPointTransaction;
use App\User;
use App\CPU\CustomerManager;
use Illuminate\Support\Facades\Mail;

class UserLoyaltyController extends Controller
{
    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            $response = ['status' => false, 'message' => 'Please fill required fields', 'data' => [], 'errors' => Helpers::error_processor($validator)];
            return response()->json($response, 200);
        }

        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        if($loyalty_point_status==1)
        {
            $user = $request->user();
            $total_loyalty_point = $user->loyalty_point;

            $loyalty_point_list = LoyaltyPointTransaction::where('user_id',$user->id);
            if($request->input('transaction_type') != "") {
                $loyalty_point_list->where('transaction_type', $request->input('transaction_type'));
            }
            $loyalty_point_list = $loyalty_point_list->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        
            $points = $loyalty_point_list->items();
            foreach ($points as $point) {
                // $point->loyalty_point = strval($point->loyalty_point);
                if($point->transaction_type == 'order_place') {
                    $point->reference = "For Order Id: " . $point->reference;
                } else {
                    $user = User::find($point->reference);
                    $point->reference = $user ? $user->name : "";
                }
            }

            $dt = [
                'limit' => (integer)$request->limit,
                'offset' => (integer)$request->offset,
                'total_loyalty_point' => $total_loyalty_point,
                'total_loyalty_point' => $loyalty_point_list->total(),
                'loyalty_point_list' => $points
            ];

            $response = ['status' => false, 'message' => 'Loyalty point lists', 'data' => [$dt], 'errors' => []];

            return response()->json($response, 200);

        }else{
            return response()->json(['message' => translate('access_denied!')], 422);
        }
    }

    public function loyalty_exchange_currency(Request $request)
    { 
        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');

        if($wallet_status != 1 || $loyalty_point_status !=1)
        {
            return response()->json([
                'message' => translate('transfer_loyalty_point_to_currency_is_not_possible_at_this_moment!')
            ],422);
        }

        $validator = Validator::make($request->all(), [
            'point' => 'required|integer|min:1'
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
    
        $user = $request->user();
        if($request->point < (int)Helpers::get_business_settings('loyalty_point_minimum_point') 
            || $request->point > $user->loyalty_point) 
        {
            return response()->json([
                'message' => translate('insufficient_point!')
            ],422);
        }

        $wallet_transaction = CustomerManager::create_wallet_transaction($user->id,$request->point,'loyalty_point','point_to_wallet');
        CustomerManager::create_loyalty_point_transaction($user->id, $wallet_transaction->transaction_id, $request->point, 'point_to_wallet');   
            
        try
        {
            
            Mail::to($user->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction));
            
                   
        }catch(\Exception $ex){
            // info($ex);
            // dd($ex);
        }

        return response()->json([
            'message' => translate('point_to_wallet_transfer_successfully!')
        ],200);  
    }
}