<?php

namespace App\Http\Controllers;

use App\ChatModel;
use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function App\getAllUsersName;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('index');
    }

    public function create()
    {
        return view('createChat', ['users' => getAllUsersName()]);
    }

    public function createChat()
    {
        /*
         * $request
         * group_name 'groupname'
         * isgroup true|false
         * created_at timestamp
         * admins ['userid', 'userid']
         * users ['userid', 'userid']
         */
        $check = ChatModel::createChat($_POST);

        if ($check['insert'] == true) {
            return redirect('chat/' . $check['uuid']);
        } else {
            echo 'Something went wrong creating a group chat.';
        }
    }

    public function viewChat($chatid)
    {
        $chats = ChatModel::getChatsByUserId(Auth::id());
        /*
         * Check if user is actually in this chat group
         */
        if (in_array($chatid, $chats)) {
            return view('chat', ['messages' => $this->fetchMessages($chatid), 'chat' => ChatModel::getChatData($chatid), 'userChats' => ChatModel::getChatsByUserId(Auth::id(), true)]);
        } else {
            return redirect('/home');
        }
    }

    public function fetchMessages($chatid)
    {
        return ChatModel::getMessages($chatid);
    }

    public static function sendMessage($data)
    {
        /*
         * Called from socket
         */

//        $data = $_POST;
//        $data['chatid'] = $chatid;

        $sendData = [];
        $sendData['userid'] = Auth::id();
        $sendData['chatid'] = $data['id'];
        $sendData['message'] = $data['msg'];

        if (empty($sendData['message'])) return 'Message empty!';

        ChatModel::saveMessage($sendData);
//        if ($chatModel->saveMessage($data)) {
//            route('chat.id', ['id' => $chatid]);
//        } else {
//            echo "Someting went wrong while sending the message.";
//        }
    }
}
