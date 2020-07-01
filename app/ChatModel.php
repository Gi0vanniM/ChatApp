<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class ChatModel extends Model
{

    /*
     * Create a chat group
     */
    public static function createChat($data)
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
            $$key = Functions::sanitize($value);
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

    /*
     * Get messages from the database by (chat id, amount (default of 30 messages))
     */
    public static function getMessages($chatid, $amount = 30)
    {
        $chatid = Functions::sanitize($chatid);
        return Functions::objectInArrayToArray(DB::select("
SELECT *
FROM (SELECT m.*, name
        FROM messages m
        JOIN users ON m.userid = users.id
        WHERE chatid=? ORDER BY timestamp
        DESC LIMIT ?) sub
ORDER BY timestamp ASC
    ", array($chatid, $amount)));
    }

    /*
     * Save a chat message to the database ($data has userid, chatid, message)
     */
    public static function saveMessage($data)
    {
        $chatid = Functions::sanitize($data['chatid']);
        $message = Functions::sanitize($data['message']);
        $userid = Functions::sanitize($data['userid']);
        return DB::insert("INSERT INTO messages (chatid, userid, timestamp, message) VALUES (?,?,?,?)", array($chatid, $userid, now(), $message));
    }

    /*
     * Get all the data about the group chat (by chat id)
     */
    public static function getChatData($id)
    {
        $id = Functions::sanitize($id);
        $result = DB::select('SELECT * FROM chats WHERE chatid=?', array($id));
        return Functions::objectInArrayToArray($result)[0];
    }

    /*
     * Get all chat groups the user is in (by user id)
     */
    public static function getChatsByUserId($id, $all = false)
    {
        /*
         * TODO: Return chat data if $all = true
         */
        $id = Functions::sanitize($id);
        $result = DB::select('SELECT * FROM chatusers WHERE userid=?', array($id));
        $chats = [];
        foreach ($result as $row) {
            $row = (array)$row;
            if (!$all) array_push($chats, $row['chatid']);
        }
        return $chats;
    }

}
