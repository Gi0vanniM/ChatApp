<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChatModel extends Model
{

    public function createChat($data)
    {
        $broodjeKaas = [];
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
            if (empty($result)) {
                $check = true;
            }
        }

        $broodjeKaas['uuid'] = $uuid;

        DB::beginTransaction();

        if ($check) {
            /*
             * insert chat data in table
             */
            $queryInsert = DB::insert("INSERT INTO chats (chatid, group_name, isgroup, created_at, admins) VALUES (:chatid, :group_name, :isgroup, NOW(), :admins)",
                array($uuid, $group_name, $isgroup, $adminList));

            DB::insert('INSERT INTO chatusers (chatid, userid) VALUES (:chatid, :userid)', array($uuid, $admins[0]));
            foreach ($users as $user) {
                DB::insert('INSERT INTO chatusers (chatid, userid) VALUES (:chatid, :userid)', array($uuid, $user));
            }


            if ($queryInsert) $broodjeKaas['insert'] = true;

        }

        DB::commit();

        return $broodjeKaas;
    }

    public function getMessages($chatid)
    {
        return DB::select("select * from messages WHERE chatid=?", array($chatid));
    }

    public function saveMessage($message)
    {
        return DB::insert("insert into messages (chatid, userid, timestamp, message) values (?,?,?,?)", array($message['chatid'], $message['userid'], now(), $message['message']));
    }

    public static function getChatData($id)
    {
        return DB::select('SELECT * FROM chats WHERE chatid=?', array($id));
    }

    public static function getChatsByUserId($id)
    {
        return DB::select('SELECT * FROM chatusers WHERE userid=?', array($id));
    }
}
