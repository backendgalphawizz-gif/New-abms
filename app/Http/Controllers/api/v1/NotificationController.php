<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\Model\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function get_notifications(Request $request)
    {
        try {

            $notifications = UserNotification::where('user_id', $request->user()->id)->orderBy('id','DESC')->get();
            foreach ($notifications as $key => $notification) {
                $notification->user_id = strval($notification->user_id);
                $notification->title = strval($notification->title);
                $notification->message = strval($notification->message);
                $notification->type = strval($notification->type);
                $notification->type_id = strval($notification->type_id);
                $notification->image = $notification->image == "-" ? '' : '';
                $notification->is_read = strval($notification->is_read);
                $notification->created_at = strval($notification->created_at);
                $notification->updated_at = strval($notification->updated_at);
            }

            $response = [
                'status' => true,
                'message' => 'Notification list',
                'data' => $notifications
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Notification list',
                'data' => []
            ];
            return response()->json($response, 200);
        }
    }

    public function mark_as_read(Request $request) {

        if($request->has('all') && $request->input('all') == 1) {
            UserNotification::where('user_id', $request->user()->id)->update(['is_read' => 1]);
        } else {
            $notification = UserNotification::find($request->input('id'));
            $notification->is_read = 1;
            $notification->save();
        }
        $response = [
            'status' => true,
            'message' => 'Marked as read',
            'data' => []
        ];
        return response()->json($response, 200);
    }

}