<?php

namespace App\Http\Controllers;

use App\ChatModel;
use App\Http\Controllers\Controller;
use App\Message;
use Illuminate\Http\Request;
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
        return view('temp_gio/createChat', ['users' => getAllUsersName()]);
    }

    public function createChat(Request $request)
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
        $chatModel->createChat($_POST);
//        var_dump($request->all());
    }

    public function viewChat($chatid)
    {
        return view('temp_gio/viewChat', ['id' => $chatid, 'messages' => $this->fetchMessages($chatid)]);
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
