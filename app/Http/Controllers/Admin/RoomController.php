<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Application;
use App\Model\Admin;
use App\Model\Room;
use App\Model\RoomUser;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Model\RoomMessage;
use App\Model\RoomUserMessage;
use Illuminate\Http\Request;

class RoomController extends Controller {
    public function index(Request $request, $type, $application_id){
        $user_id = auth('admin')->user()->id;
        return view('admin-views.rooms.index', compact('type', 'application_id', 'user_id'));
    }

    public function getChatHistory(Request $request) {
        $user_id = $request->input('user_id');
        $admin_id = Admin::where('admin_role_id', 1)->first()->id;;
        $type = $request->input('type');
        $application_id = $request->input('application_id');
        $application = Application::find($application_id);
        $room = Room::where(['room_type' => $type, 'application_id' => $application_id])->first();

        if($room == null) {
            $userIds = Helpers::getAssignedUserIds($application, $type);

            $userIds[] = $admin_id;

            $room = Helpers::createRoom($application_id, $type);

            Helpers::createRoomUsers($room->id, $userIds);
        }

        $user_room = RoomUser::where(['room_id' => $room->id, 'user_id' => $user_id])->first();
        $user_room_messages = RoomUserMessage::with('message.user')->where(['room_id' => $room->id, 'user_id' => $user_id])->orderBy('id', 'DESC')->get();

        return response()->json(['status' => true, 'room' => $room, 'user_rooms' => $user_room, "user_room_messages" => $user_room_messages]);
    }

    public function sendMessage(Request $request) {
        $user_id = auth('admin')->user()->id;
        $room_id = $request->input('room_id');
        $message = $request->input('message');
        $message_type = $request->input('message_type') ?? 'text';

        $roomMessage = new RoomMessage;
        $roomMessage->room_id = $room_id;
        $roomMessage->created_by = $user_id;
        $roomMessage->message = $message;
        $roomMessage->message_type = $message_type;
        $file = '';
        if ($request->has('file')) {
            $fileObj   = $request->file('file');
            $extension = $fileObj->getClientOriginalExtension();
            $file = ImageManager::upload('admin/', $extension, $request->file('file'));

            
        }
        if($message == '') {
            $roomMessage->message = $file ?? 'File Sent';
        }
        $roomMessage->file = $file;
        $roomMessage->save();

        $roomUsers = RoomUser::where(['room_id' => $room_id])->pluck('user_id')->toArray();

        $bulkData = [];
        foreach ($roomUsers as $roomUserId) {
            $bulkData[] = [
                'room_id'     => $room_id,
                'user_id'     => $roomUserId,
                'room_message_id'  => $roomMessage->id,
                'is_read'     => 0
            ];
        }
        RoomUserMessage::insert($bulkData);
        
        $user_room_messages = RoomUserMessage::with('message.user')->where(['room_id' => $room_id, 'user_id' => $user_id])->orderBy('id', 'DESC')->get();
        
        return response()->json(['status' => true, "user_room_messages" => $user_room_messages]);

    }

    public function deleteMessage(Request $request) {
        $message_id = $request->input('message_id');
        RoomUserMessage::where('room_message_id', $message_id)->delete();
        RoomMessage::where('id', $message_id)->delete();

        return response()->json(['status' => true, 'message' => 'Message deleted successfully']);
    }

}