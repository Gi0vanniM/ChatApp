<?php
$chatname = 'hoi';

$lastmsg = 'hoei';

if (empty($noChatActive)) $noChatActive = false;

$userid = $name = $chat_id = '';

if (!$noChatActive) {
    if (isset($chat)) $chat = (array)$chat;
    if (isset($userChats)) $userChats = (array)$userChats;
    if (!empty($messages)) $messages = (array)$messages;

    $userid = \Illuminate\Support\Facades\Auth::id(); // de id is de id van de gebruiker
    $name = \Illuminate\Support\Facades\Auth::user()->name; // de naam van de gebruiker
    $chat_id = $chat['chatid']; // de id van de chat (waar deze user aanvast zit)
}
?>

@extends('layouts.app')

<script src="../js/client-conn.js"></script>
<script>
    const SELF = new SocketCLT("<?=$chat_id?>", "<?=$name?>", <?=$userid?>);
</script>

@section('content')

    <div class='col-12 w-100 mx-auto p-0 text-light position-absolute row mainbackground' style="height: 91%;">

        <!--Main overview of chats for user -->

        <div class='col-2 h-75 mt-2 ml-auto border border-secondary rounded overflow-auto'>
            <?php // foreach ($chatvar as $row){ ?>
            <div class="card bg-dark shadow-lg mt-1">

                <div class="card-body">
                    <h4 class="card-title">To: <?= $chatname ?> </h4>
                    <p class="card-text"><?= $lastmsg ?></p>
                </div>
            </div>
            <?php // } ?>
        </div>


        <!-- Main chat window-->
        <div class="ml-auto mr-1 col-9 h-75 border border-secondary rounded mt-2 shadow-lg overflow-auto" id="chat_box">
            <?php if (!$noChatActive) {?>
            <h3 class="mt-1 bg-dark rounded text-center shadow-lg position-sticky">Chat with: <?= $chat['group_name'] ?> </h3>
            <?php foreach($messages as $message) { ?>
            <div class="card bg-dark shadow-lg mt-1">
                <div class="card-body p-1">
                <?php if ($message['userid'] == $userid) {?><!-- begin an if statement here to check whether the owner of the message is person viewing it, if that's the case then show this menu -->
                    <div class="dropdown mr-auto position-relative float-right">
                        <button class="btn btn-secondary dropdown-toggle"
                                type="button" id="dropdownMenu1" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            ...
                        </button>

                        <div class="dropdown-menu dropdown-menu-right bg-dark w-25" aria-labelledby="dropdownMenu1">
                            <a class="dropdown-item text-white" href="#!">Edit</a>
                            <a class="dropdown-item text-white" href="#!">Delete</a>
                        </div>

                    </div>
                <?php } ?><!-- end said if statement here -->
                    <h6 class="card-text text-white"><?= ($message['userid'] == $userid) ? '<strong>You</strong>' : $message['name'] ?>
                        : </h6>
                    <h6 class="card-text text-white"><?= $lastmsg ?></h6>
                    <small class='text-secondary'>At <?= $message['timestamp'] ?></small>
                </div>
            </div>
            <?php } ?>
            <?php } ?>
        </div>
        <div class='row ml-auto mr-1 col-12'>

            <a href="/create" style="height: 30%" class="mx-auto">
                <button class="btn btn-success text-white mb-1"> Make a group chat</button>
            </a>

            <?php if (!$noChatActive) { ?>
            <form id="chat_form" action="javascript:void(0);"
                  onsubmit="SELF.send(document.getElementById('msg_box').value); " class=" ml-auto w-75">
                <div class="form-group shadow-lg">
                    <input type="text" name="msg" id="msg_box"
                           class="form-control bg-dark text-white border border-secondary rounded"
                           placeholder="Send a message" aria-describedby="helpId">
                </div>
                <button type="submit" class="btn btn-primary ml-auto shadow float-left">Send</button>
            </form>
            <?php } ?>
        </div>
    </div>
@endsection
