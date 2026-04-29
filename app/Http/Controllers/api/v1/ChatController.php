<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Chatting;
use App\Model\DeliveryMan;
use App\Model\Application;
use App\Model\Seller;
use App\Model\Shop;
use App\Model\Admin;
use App\CPU\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class ChatController extends Controller
{

    public function list(Request $request)
    {
        $user = $request->user();
        $limit = (int) ($request->limit ?? 10);
        $page  = (int) ($request->offset ?? 1);

        $applications = Application::where('user_id', $user->id)
            ->where('status', 'schedule')
            ->paginate($limit, ['*'], 'page', $page);

        $applicationIds = $applications->pluck('id')->toArray();

        $existingChats = Chatting::where('user_id', $user->id)
            ->whereIn('application_id', $applicationIds)
            ->select('application_id', DB::raw('MAX(id) as last_id'))
            ->groupBy('application_id')
            ->pluck('last_id', 'application_id');

        $chats = [];

        foreach ($applications as $app) {

            if (isset($existingChats[$app->id])) {

                $chat = Chatting::with('admin')
                    ->where('id', $existingChats[$app->id])
                    ->first();

                if ($chat) {
                    $chat->application_id = $app->id;
                    $chat->reference_number = $app->reference_number;
                    $chat->status = $app->status;
                    $chats[] = $chat;
                    continue;
                }
            }

            $dummy = new \stdClass();
            $dummy->id = null;
            $dummy->application_id = $app->id;
            $dummy->reference_number = $app->reference_number;
            $dummy->status = $app->status;

            $dummy->user_id = $user->id;
            $dummy->admin_id = 1;
            $dummy->message = "No conversation yet";

            $dummy->sent_by_admin = 0;
            $dummy->sent_by_user  = 0;
            $dummy->seen_by_admin = 0;
            $dummy->seen_by_user  = 0;

            $dummy->created_at = now();
            $dummy->updated_at = now();

            $dummy->admin = Admin::find(1);

            $chats[] = $dummy;
        }

        return response()->json([
            'total_size' => Application::where('user_id', $user->id)
                ->where('status', 'schedule')
                ->count(),
            'limit' => $limit,
            'offset' => $page,
            'chat' => $chats
        ]);
    }



    // public function list(Request $request, $type)
    // {
    //     if ($type == 'delivery-man') {
    //         $id_param = 'delivery_man_id';
    //         $with = 'delivery_man';
    //     } elseif ($type == 'seller') {
    //         $id_param = 'seller_id';
    //         $with = 'seller_info.shops';
    //     } elseif ($type == 'admin') {
    //         $id_param = 'admin_id';
    //         $with = 'admin';
    //     } else {
    //         return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
    //     }

    //     $total_size = Chatting::where(['user_id' => $request->user()->id])
    //         ->whereNotNull($id_param)
    //         ->select($id_param)
    //         ->distinct()
    //         ->get()
    //         ->count();

    //     $unique_chat_ids = Chatting::where(['user_id' => $request->user()->id])
    //         ->whereNotNull($id_param)
    //         ->select($id_param)
    //         ->distinct()
    //         ->paginate($request->limit, ['*'], 'page', $request->offset);

    //     $chats = array();
    //     if ($unique_chat_ids) {
    //         foreach ($unique_chat_ids as $unique_chat_id) {
    //             $chats[] = Chatting::with([$with])
    //                 ->where(['user_id' => $request->user()->id, $id_param => $unique_chat_id->$id_param])
    //                 ->whereNotNull($id_param)
    //                 ->latest()
    //                 ->first();
    //         }
    //     }

    //     $data = array();
    //     $data['total_size'] = $total_size;
    //     $data['limit'] = $request->limit;
    //     $data['offset'] = $request->offset;
    //     $data['chat'] = $chats;

    //     return response()->json($data, 200);
    // }

    public function search(Request $request, $type)
    {
        $terms = explode(" ", $request->input('search'));
        if ($type == 'seller') {
            $id_param = 'seller_id';
            $with_param = 'seller_info.shops';
            $users = Shop::when($request->search, function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->where('name', 'like', '%' . $term . '%');
                }
            })->pluck('seller_id')->toArray();
        } elseif ($type == 'delivery-man') {
            $with_param = 'delivery_man';
            $id_param = 'delivery_man_id';
            $users = DeliveryMan::when($request->search, function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->where('f_name', 'like', '%' . $term . '%')
                        ->orWhere('l_name', 'like', '%' . $term . '%');
                }
            })->pluck('id')->toArray();
        } elseif ($type == 'admim') {
            $with_param = 'admin';
            $id_param = 'admin_id';
            $users = Admin::when($request->search, function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->where('f_name', 'like', '%' . $term . '%')
                        ->orWhere('l_name', 'like', '%' . $term . '%');
                }
            })->pluck('id')->toArray();
        } else {
            return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
        }

        $unique_chat_ids = Chatting::where(['user_id' => $request->user()->id])
            ->whereIn($id_param, $users)
            ->select($id_param)
            ->distinct()
            ->get()
            ->toArray();
        $unique_chat_ids = call_user_func_array('array_merge', $unique_chat_ids);

        $chats = array();
        if ($unique_chat_ids) {
            foreach ($unique_chat_ids as $unique_chat_id) {
                $chats[] = Chatting::with([$with_param])
                    ->where(['user_id' => $request->user()->id, $id_param => $unique_chat_id])
                    ->whereNotNull($id_param)
                    ->latest()
                    ->first();
            }
        }

        return response()->json($chats, 200);
    }

    public function get_message(Request $request, $type, $application_id)
    {

        $validator = Validator::make($request->all(), [
            // 'offset' => 'required',
            // 'limit'  => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $application = Application::with('user')->find($application_id);

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        $user_id = $application->user_id;

        $query = Chatting::where('application_id', $application_id);

        $existing_messages = $query->count();

        if ($existing_messages == 0) {
            $dummy = new \stdClass();
            $dummy->id = null;
            $dummy->application_id = $application_id;
            $dummy->user_id = $user_id;
            $dummy->message = "No conversation yet";
            $dummy->sent_by_user = 0;
            $dummy->sent_by_admin = 0;
            $dummy->seen_by_user = 0;
            $dummy->seen_by_admin = 0;
            $dummy->status = 1;
            $dummy->created_at = now();
            $dummy->updated_at = now();
            $dummy->user = $application->user;

            return response()->json([
                'total_size' => 1,
                'limit'      => $request->limit ?? 1000000,
                'offset'     => $request->offset ?? 1,
                'message'    => [$dummy],
            ], 200);
        }

        

        $messages = $query->orderBy('id', 'DESC')->paginate($request->limit, ['*'], 'page', $request->offset ?? 1);

        // $messages->getCollection()->transform(function ($message) {
        //     if (!empty($message->file)) {
        //         $message->file = asset('storage/app/public/admin/' . $message->file);
        //     }
        //     return $message;
        // });

        Chatting::where('application_id', $application_id)
            ->where('sent_by_admin', 1)
            ->update(['seen_by_customer' => 1]);

        return response()->json([
            'total_size' => $messages->total(),
            'limit'      => $request->limit ?? 1000000,
            'offset'     => $request->offset ?? 1,
            'message'    => $messages->items(),
        ], 200);
    }


    // public function get_message(Request $request, $type, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'offset' => 'required',
    //         'limit' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => Helpers::error_processor($validator)], 403);
    //     }

    //     if ($type == 'delivery-man') {
    //         $id_param = 'delivery_man_id';
    //         $sent_by = 'sent_by_delivery_man';
    //         $with = 'delivery_man';
    //     } elseif ($type == 'seller') {
    //         $id_param = 'seller_id';
    //         $sent_by = 'sent_by_seller';
    //         $with = 'seller_info.shops';
    //     } elseif ($type == 'admin') {
    //         $id_param = 'admin_id';
    //         $sent_by = 'sent_by_admin';
    //         $with = 'admin';
    //     } else {
    //         return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
    //     }

    //     $query = Chatting::with($with)->where(['user_id' => $request->user()->id, $id_param => $id]);

    //     if (!empty($query->get())) {
    //         $message = $query->paginate($request->limit, ['*'], 'page', $request->offset);

    //         $query->where($sent_by, 1)->update(['seen_by_customer' => 1]);

    //         $data = array();
    //         $data['total_size'] = $message->total();
    //         $data['limit'] = $request->limit;
    //         $data['offset'] = $request->offset;
    //         $data['message'] = $message->items();
    //         return response()->json($data, 200);
    //     }
    //     return response()->json(['message' => translate('no messages found!')], 200);
    // }

    public function send_message(Request $request, $type)
    {
        $validator = Validator::make($request->all(), [
            // 'id' => 'required',
            'application_id' => 'required',
            'message' => 'required',
        ], [
            'message.required' => translate('type something!')
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $file = '';
        if ($request->has('file')) {
            $fileObj   = $request->file('file');
            $extension = $fileObj->getClientOriginalExtension();
            $file = ImageManager::upload('admin/', $extension, $request->file('file'));
        }
        $chatting = new Chatting();
        $chatting->user_id = $request->user()->id;
        $chatting->message = $request->message;
        $chatting->file = $file;
        $chatting->sent_by_customer = 1;
        $chatting->seen_by_customer = 1;
        if ($type == 'seller') {
            $seller = Seller::with('shop')->find($request->id);
            $chatting->seller_id = $request->id;
            $chatting->shop_id = $seller->shop->id;
            $chatting->seen_by_seller = 0;

            $fcm_token = $seller->cm_firebase_token;
        } elseif ($type == 'delivery-man') {
            $chatting->delivery_man_id = $request->id;
            $chatting->seen_by_delivery_man = 0;

            $dm = DeliveryMan::find($request->id);
            $fcm_token = $dm->fcm_token;
        } elseif ($type == 'admin') {
            $chatting->admin_id = $request->id;
            $chatting->seen_by_admin = 0;
            $chatting->application_id =$request->application_id;
            $fcm_token = "";
        } else {
            return response()->json(translate('Invalid Chatting Type!'), 403);
        }

        if ($chatting->save()) {
            if (!empty($fcm_token)) {
                $data = [
                    'title' => translate('message'),
                    'description' => $request->message,
                    'order_id' => '',
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }

            return response()->json(['message' => $request->message, 'file' => asset('storage/app/public/admin/' . $file), 'time' => now()], 200);
        } else {
            return response()->json(['message' => translate('Message sending failed')], 403);
        }
    }

    public function get_admin_message(Request $request, $type, $id)
    {

        if ($type == 'admin') {
            $id_param = 'admin_id';
            $sent_by = 'sent_by_admin';
        } else {
            return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
        }

        // $query = Chatting::select('admin_id', 'user_id', 'message','sent_by_customer', 'sent_by_admin')->where(['user_id' => $request->user()->id, $id_param => $id]);

        Chatting::where(['admin_id' => 0, 'user_id' => $request->user_id])
            ->update([
                'seen_by_customer' => 1
            ]);

        $query = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
            ->select('admin_id', 'user_id', 'message', 'sent_by_customer', 'sent_by_admin', 'chattings.created_at')
            ->where('chattings.admin_id', 0)
            ->where('chattings.user_id', $request->user()->id)
            ->orderBy('chattings.created_at', 'ASC');


        if (!empty($query->get())) {

            $message = $query->paginate(1000, ['*'], 'page', 0);

            $query->where($sent_by, 1)->update(['seen_by_customer' => 1]);

            $lists = $message->items();

            foreach ($lists as $list) {
                // $list->admin;
                // $list->admin->email_verified_at = $list->admin->email_verified_at ?? "";
                // $list->admin->image = asset('storage/app/public/admin/' . $list->admin->image);
                $list->sent_by_admin = $list->sent_by_admin ?? 0;
                $list->sent_by_customer = $list->sent_by_customer ?? 0;
            }

            $data = array();
            $data['total_size'] = $message->total();
            $data['limit'] = $request->limit;
            $data['offset'] = $request->offset;
            $data['message'] = $message->items();
            return response()->json($data, 200);
        }
        return response()->json(['message' => translate('no messages found!')], 200);
    }

    public function send_admin_message(Request $request, $type)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'message' => 'required',
        ], [
            'message.required' => translate('type something!')
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $chatting = new Chatting();
        $chatting->user_id = $request->user()->id;
        $chatting->message = $request->message;
        $chatting->sent_by_customer = 1;
        $chatting->seen_by_customer = 1;

        if ($type == 'seller') {
            $seller = Seller::with('shop')->find($request->id);
            $chatting->seller_id = $request->id;
            $chatting->shop_id = $seller->shop->id;
            $chatting->seen_by_seller = 0;

            $fcm_token = $seller->cm_firebase_token;
        } elseif ($type == 'delivery-man') {
            $chatting->delivery_man_id = $request->id;
            $chatting->seen_by_delivery_man = 0;

            $dm = DeliveryMan::find($request->id);
            $fcm_token = $dm->fcm_token;
        } elseif ($type == 'admin') {
            $chatting->admin_id = 0;
            $chatting->seen_by_admin = 0;

            // $dm = DeliveryMan::find($request->id);
            $fcm_token = ""; // $dm->fcm_token;
        } else {
            return response()->json(translate('Invalid Chatting Type!'), 403);
        }

        if ($chatting->save()) {
            if (!empty($fcm_token)) {
                $data = [
                    'title' => translate('message'),
                    'description' => $request->message,
                    'order_id' => '',
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }

            return response()->json(['message' => $request->message, 'time' => now()], 200);
        } else {
            return response()->json(['message' => translate('Message sending failed')], 403);
        }
    }

    public function deleteChat(Request $request, $id) {
        $chat = Chatting::find($id);
        if($chat) {
            $chat->delete();
        }

        return response()->json(['status' => true, 'message' => 'Chat deleted success']);

    }

}
