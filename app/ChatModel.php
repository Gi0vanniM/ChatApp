<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChatModel extends Model
{

    public function createChat($data)
    {

        /*
         * $request
         * group_name 'groupname'
         * isgroup true|false
         * created_at timestamp
         * admins ['userid', 'userid']
         * users ['userid', 'userid']
         */

        foreach ($data as $key => $value) {
            if (empty($value) && $key != 'group_name') exit("$key was not filled in.");
            $$key = $value;
        }

        if (empty($group_name)) $group_name = "";
//        if (empty($isgroup)) {
//            $isgroup = false;
//        } else {
//            $isgroup = true;
//        }
        $isgroup = (empty($isgroup)) ? false : true;
        if (isset($admins)) $admins = $admins;
        if (isset($users)) $users = $users;

        $adminList = implode(';', $admins);
        $userList = implode(';', $users);
        var_dump($adminList);

//        DB::insert("insert into chats (group_name, isgroup, created_at, admins, users) values (:group_name, :isgroup, NOW(), :admins, :users)",
//            array($group_name, $isgroup, $admins, $users));

    }

    public function getMessages($chatid)
    {
        $chat = 'chat-' . $chatid;
        return DB::select("select * from " . "`$chat`");
    }

    public function saveMessage($message)
    {
        $chat = 'chat-' . $message['chatid'];
        DB::insert("insert into $chat (userid, timestamp, message) values (?,?,?)", array($message['chatid'], $message['userid'], now(), $message['message']));
    }
}
