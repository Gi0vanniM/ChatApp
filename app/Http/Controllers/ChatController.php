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
        return view('chat', ['messages' => $this->fetchMessages($chatid), 'chat' => ChatModel::getChatData($chatid), 'userChats' => ChatModel::getChatsByUserId(Auth::id())]);
    }

    public function fetchMessages($chatid)
    {
        $chat = new ChatModel();
        return $chat->getMessages($chatid);
    }

    public function sendMessage(Request $request)
    {
        /*
         * Call socket function here*
         */
        saveMessage($request);
    }
}
