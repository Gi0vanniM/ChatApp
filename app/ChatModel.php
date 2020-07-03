<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class ChatModel extends Model
{

    /**
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
            if (empty($value)) exit("$key was not filled in.");
            $$key = $value;
        }

        $group_name = Functions::sanitize($data['group_name']);
        $isgroup = Functions::sanitize($data['isgroup']);

        $isgroup = (empty($isgroup)) ? false : true;
        $adminList = Functions::sanitize(implode(';', $data['admins']));

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

            DB::insert('INSERT INTO chatusers (chatid, userid) VALUES (:chatid, :userid)', array($uuid, $data['admins'][0]));
            foreach ($data['users'] as $user) {
                DB::insert('INSERT INTO chatusers (chatid, userid) VALUES (:chatid, :userid)', array($uuid, $user));
            }


            if ($queryInsert) $broodjeKaas['insert'] = true;

        }

        DB::commit();

        return $broodjeKaas;
    }

    /**
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

    /**
     * Save a chat message to the database ($data has userid, chatid, message)
     */
    public static function saveMessage($data)
    {
        $chatid = Functions::sanitize($data['chatid']);
        $message = Functions::sanitize($data['message']);
        $userid = Functions::sanitize($data['userid']);
        return DB::insert("INSERT INTO messages (chatid, userid, timestamp, message) VALUES (?,?,?,?)", array($chatid, $userid, now(), $message));
    }

    /**
     * Get all the data about the group chat (by chat id)
     */
    public static function getChatData($id)
    {
        $id = Functions::sanitize($id);
        $result = DB::select('SELECT * FROM chats WHERE chatid=?', array($id));
        return Functions::objectInArrayToArray($result)[0];
    }

    /**
     * Get all chat groups the user is in (by user id)
     */
    public static function getChatsByUserId($id, $all = false)
    {
        /*
         * TODO: Return chat data if $all = true
         */
        $id = Functions::sanitize($id);

        $allChats = '
SELECT c.userid, ch.*, (SELECT message FROM messages WHERE chatid=c.chatid ORDER BY timestamp DESC LIMIT 1) as last_message
FROM chatusers c JOIN chats ch ON c.chatid = ch.chatid WHERE userid=?';
        $listChats = 'SELECT * FROM chatusers WHERE userid=?';

        $result = DB::select(($all) ? $allChats : $listChats, array($id));
        $chats = [];

        foreach ($result as $row) {
            $row = (array)$row;
            if (!$all) array_push($chats, $row['chatid']);
        }
        $result = Functions::objectInArrayToArray($result);
        return ($all) ? $result : $chats;
    }

    /**
     * Let a user leave the chat
     */
    public static function leaveChat($userid, $chatid)
    {
        //$result = DB::delete('SELECT * FROM chats WHERE chatid=?', array($id));
        $query = DB::delete('DELETE FROM chatusers WHERE chatid=? AND userid=?', array($chatid, $userid));
        if ($query) return true;
        return false;
    }
}
