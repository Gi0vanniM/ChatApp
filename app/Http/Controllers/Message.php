<?php

namespace App\Http\Controllers;

use App\ChatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Message extends Controller
{
    public static function sendMessage()
    {
        $data = $_POST;
        error_log('sendMessage called');
        /*
         * Called from socket
         */

//        $data = $_POST;
//        $data['chatid'] = $chatid;

        $sendData = [];
//        $sendData['userid'] = Auth::id();
        $sendData['chatid'] = $data['chatid'];
        $sendData['message'] = $data['message'];
        $sendData['userid'] = $data['userid'];

        if (empty($sendData['message'])) return 'Message empty!';

        ChatModel::saveMessage($sendData);
//        if ($chatModel->saveMessage($data)) {
//            route('chat.id', ['id' => $chatid]);
//        } else {
//            echo "Someting went wrong while sending the message.";
//        }
    }
}
