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

        $isgroup = (empty($isgroup)) ? false : true;
        if (isset($admins)) $admins = $admins;
        if (isset($users)) $users = $users;

        $adminList = implode(';', $admins);
        $userList = implode(';', $users);


        $check = false;
        while ($check == false) {

            $uuid = uniqid();
            $result = DB::select('SELECT * FROM chats WHERE chatid=?', array($uuid));
            var_dump($result);
            if (empty($result)) {
                $check = true;
                echo "Using $uuid";
            }
        }

        if ($check) {
            DB::insert("insert into chats (chatid, group_name, isgroup, created_at, admins, users) values (:chatid, :group_name, :isgroup, NOW(), :admins, :users)",
                array($uuid, $group_name, $isgroup, $adminList, $userList));
//            DB::table('CREATE TABLE ');
        }

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
