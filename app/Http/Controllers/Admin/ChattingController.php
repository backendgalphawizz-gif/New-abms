<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Chatting;
use App\Model\Application;
use App\Model\Admin;
use App\Model\DeliveryMan;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;

class ChattingController extends Controller
{
    /**
     * chatting list
     */
    public function chat(Request $request)
    {
        $last_chat = Chatting::where('admin_id', 0)
            ->whereNotNull(['delivery_man_id', 'admin_id'])
            ->orderBy('created_at', 'DESC')
            ->first();

        if (isset($last_chat)) {
            Chatting::where(['admin_id' => 0, 'delivery_man_id' => $last_chat->delivery_man_id])->update([
                'seen_by_admin' => 1
            ]);


            $chattings = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
                ->where('chattings.admin_id', 0)
                ->where('delivery_man_id', $last_chat->delivery_man_id)
                ->orderBy('chattings.created_at', 'desc')
                ->get();

            $chattings_user = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image', 'delivery_men.phone')
                ->where('chattings.admin_id', 0)
                ->orderBy('chattings.created_at', 'desc')
                ->get()
                ->unique('delivery_man_id');

            return view('admin-views.delivery-man.chat', compact('chattings', 'chattings_user', 'last_chat'));
        }

        return view('admin-views.delivery-man.chat', compact('last_chat'));
    }

    /**
     * ajax request - get message by delivery man
     */
    public function ajax_message_by_delivery_man(Request $request)
    {

        Chatting::where(['admin_id' => 0, 'delivery_man_id' => $request->delivery_man_id])
            ->update([
                'seen_by_admin' => 1
            ]);

        $sellers = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
            ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
            ->where('chattings.admin_id', 0)
            ->where('chattings.delivery_man_id', $request->delivery_man_id)
            ->orderBy('created_at', 'ASC')
            ->get();

        return response()->json($sellers);
    }

    /**
     * ajax request - Store massage for deliveryman
     */
    public function ajax_admin_message_store(Request $request)
    {
        if ($request->message == '') {
            Toastr::warning('Type Something!');
            return response()->json(['message' => 'type something!']);
        }

        $message = $request->message;
        $time = now();

        Chatting::create([
            'delivery_man_id' => $request->delivery_man_id,
            'admin_id' => 0,
            'message' => $request->message,
            'sent_by_admin' => 1,
            'seen_by_admin' => 1,
            'created_at' => now(),
        ]);

        $dm = DeliveryMan::find($request->delivery_man_id);

        if (!empty($dm->fcm_token)) {
            $data = [
                'title' => translate('message'),
                'description' => $request->message,
                'order_id' => '',
                'image' => '',
            ];
            Helpers::send_push_notif_to_device($dm->fcm_token, $data);
        }

        return response()->json(['message' => $message, 'time' => $time]);
    }



    public function assessment_details()
    {
        return view('admin-views.applicants.assessment-details');
    }
    public function assessment()
    {
        return view('admin-views.applicants.assessment');
    }
    public function allview()
    {
        return view('admin-views.applicants.allview');
    }

    public function user_chat(Request $request, $application_id = null)
    {
        $query = Application::where('applications.status', 'schedule')
            ->join('users', 'users.id', '=', 'applications.user_id')
            ->leftJoin('chattings', 'applications.id', '=', 'chattings.application_id')
            ->select(
                'applications.id as application_id',
                'applications.reference_number',
                'applications.status',
                'applications.user_id',
                'users.f_name',
                'users.l_name',
                'users.image',
                'users.phone',
                DB::raw('MAX(chattings.created_at) as last_chat_time')
            )
            ->groupBy(
                'applications.id',
                'applications.reference_number',
                'applications.status',
                'applications.user_id',
                'users.f_name',
                'users.l_name',
                'users.image',
                'users.phone'
            );

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('users.f_name', 'LIKE', "%{$search}%")
                    ->orWhere('users.l_name', 'LIKE', "%{$search}%")
                    ->orWhere('applications.reference_number', 'LIKE', "%{$search}%")
                    ->orWhere('users.phone', 'LIKE', "%{$search}%");
            });

            $applications = $query->orderBy('last_chat_time', 'DESC')->get();
        } else {

            $applications = $query->orderBy('last_chat_time', 'DESC')->limit(10)->get();
        }

        $last_app = $application_id
            ? $applications->where('application_id', $application_id)->first()
            : $applications->first();

        $admin = Admin::with('role')->where('id', auth('admin')->user()->id)->first();

        if ($request->ajax()) {
            return view('admin-views.customer.app-list', compact('applications'))->render();
        }

        return view('admin-views.customer.chat', [
            'applications' => $applications,
            'last_app' => $last_app,
            'admin' => $admin
        ]);
    }

    public function delete_chat(Request $request, $message_id = null)
    {
        Chatting::where('id', $message_id)->delete();
        Toastr::success(translate('Message deleted successfully!'));
        return response()->json(['message' => 'Message deleted successfully!']);
    }
    public function delete_all(Request $request, $application_id = null)
    {
        Chatting::where('application_id', $application_id)->delete();
        Toastr::success(translate('All messages deleted successfully!'));
        return response()->json(['message' => 'All messages deleted successfully!']);
    }

    public function ajax_user_chat(Request $request)
    {
        $application_id = $request->application_id;

        // Admin has seen messages
        Chatting::where('application_id', $application_id)
            ->update(['seen_by_admin' => 1]);

        $messages = Chatting::where('application_id', $application_id)
            ->orderBy('created_at', 'Desc')
            ->get()
            ->map(function ($msg) {

                $senderName = $msg->sent_by_admin && $msg->admin_id
                    ? optional(Admin::find($msg->admin_id))->name // safe fetch
                    : null;

                $adminRole = $msg->sent_by_admin && $msg->admin_id
                    ? optional(Admin::with('role')->find($msg->admin_id))->role->name // safe fetch
                    : null;

                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'sent_by_admin' => $msg->sent_by_admin,
                    'admin_name' => $senderName,
                    'admin_role' => $adminRole,
                    'created_at' => $msg->created_at->format('j M Y h:i A'),
                    'seen' => $msg->seen_by_customer == 1
                ];
            });

        return response()->json($messages);
    }



    public function store_user_chat(Request $request)
    {
        $message = Chatting::create([
            'user_id' => $request->user_id,
            'application_id' => $request->application_id,
            'admin_id' => auth('admin')->user()->id,
            'message' => $request->message,
            'sent_by_admin' => 1
        ]);

        return response()->json([
            'message' => $message->message,
            'time' => $message->created_at->format('j M Y h:i A')
        ]);
    }




    // public function user_chat(Request $request)
    // {
    //     $last_chat = Chatting::whereNotNull(['user_id', 'admin_id'])
    //         ->where('admin_id', 0)
    //         ->orderBy('created_at', 'DESC')
    //         ->first();

    //     if (isset($last_chat)) {
    //         Chatting::where(['admin_id' => 0, 'user_id' => $last_chat->user_id])->update([
    //             'seen_by_admin' => 1
    //         ]);


    //         $chattings = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
    //             ->select('chattings.*', 'users.l_name', 'users.name as f_name', 'users.image')
    //             ->where('chattings.admin_id', 0)
    //             ->where('user_id', $last_chat->user_id)
    //             ->orderBy('chattings.created_at', 'desc')
    //             ->get();

    //         $chattings_user = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
    //             ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image', 'users.phone')
    //             ->where('chattings.admin_id', 0)
    //             ->orderBy('chattings.created_at', 'desc')
    //             ->get()
    //             ->unique('user_id');

    //         return view('admin-views.customer.chat', compact('chattings', 'chattings_user', 'last_chat'));
    //     }

    //     return view('admin-views.customer.chat', compact('last_chat'));
    // }


    /**
     * ajax request - get message by customer
     */
    public function ajax_message_by_customer(Request $request)
    {

        Chatting::where(['admin_id' => 0, 'user_id' => $request->user_id])
            ->update([
                'seen_by_admin' => 1
            ]);

        $sellers = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
            ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image')
            ->where('chattings.admin_id', 0)
            ->where('chattings.user_id', $request->user_id)
            ->orderBy('created_at', 'ASC')
            ->get();

        return response()->json($sellers);
    }

    /**
     * ajax request - Store massage for deliveryman
     */
    public function ajax_admin_message_store_customer(Request $request)
    {
        if ($request->message == '') {
            Toastr::warning('Type Something!');
            return response()->json(['message' => 'type something!']);
        }

        $message = $request->message;
        $time = now();

        Chatting::create([
            'user_id' => $request->delivery_man_id,
            'admin_id' => 0,
            'message' => $request->message,
            'sent_by_admin' => 1,
            'seen_by_admin' => 1,
            'created_at' => now(),
        ]);

        $dm = User::find($request->delivery_man_id);

        if (!empty($dm->fcm_token)) {
            $data = [
                'title' => translate('message'),
                'description' => $request->message,
                'order_id' => '',
                'image' => '',
            ];
            Helpers::send_push_notif_to_device($dm->fcm_token, $data);
        }

        return response()->json(['message' => $message, 'time' => $time]);
    }
}
