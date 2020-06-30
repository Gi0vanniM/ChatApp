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
        return view('chats');
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
        $chatModel = new ChatModel();
        $broodje = $chatModel->createChat($_POST);
        if ($broodje['insert'] == true) {
            return redirect('chat/' . $broodje['uuid']);
        } else {

        }
    }

    public function viewChat($chatid)
    {
        $chats = ChatModel::getChatsByUserId(Auth::id());
        /*
         * Check if user is actually in this chat group
         */
        if (in_array($chatid, $chats)) {
            return view('chat', ['messages' => $this->fetchMessages($chatid), 'chat' => ChatModel::getChatData($chatid), 'userChats' => $chats]);
        } else {
            return redirect('/home');
        }

    }

    public function fetchMessages($chatid)
    {
        $chat = new ChatModel();
        return $chat->getMessages($chatid);
    }

    public function sendMessage($chatid)
    {
        /*
         * Call socket function here*
         */

        $data = $_POST;
        $data['chatid'] = $chatid;
        $data['userid'] = Auth::id();

        $chatModel = new ChatModel();
        if ($chatModel->saveMessage($data)) {
            route('chat.id', ['id' => $chatid]);
        } else {
            echo "Someting went wrong while sending the message.";
        }
    }
}
