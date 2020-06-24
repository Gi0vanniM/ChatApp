<?php
$chatname = 'hoi';

$lastmsg = 'hoei';
?>

@extends('layouts.app')

@section('content')
<div class='col-12 h-100 w-100 mx-auto p-0 text-light position-absolute row mainbackground' style="background-image:url({{asset('img/bg.png')}}); background-repeat: no-repeat; background-size: cover;">

<!--Main overview of chats for user -->

<div class='col-2 h-75 mt-2 ml-auto border border-secondary rounded overflow-auto'>
<?php // foreach ($chatvar as $row){ ?>
    <div class="card bg-dark shadow-lg mt-1">
    <img class="card-img-top" src="" alt="">
    <div class="card-body">
        <h4 class="card-title">To: <?= $chatname ?> </h4>
        <p class="card-text"><?= $lastmsg ?></p>
    </div>
</div>
<?php // } ?>
</div>

<div class="ml-auto mr-1 col-9 h-75 border border-secondary rounded mt-2 shadow-lg">
<h3 class="mt-1 bg-dark rounded text-center shadow-lg">Chat with: User 1</h3>
<?php // foreach($chatmsg as row) { ?>
<div class="card bg-dark shadow-lg mt-1">
    <img class="card-img-top" src="" alt="">
    <div class="card-body p-1">
        <h6 class="card-text text-white">User 1:</h6>
        <h6 class="card-text text-white"><?= $lastmsg ?></h6>
        <small class='text-secondary'>At [time and date]</small>
    </div>
</div>

<?php // } ?>
</div>
<div class='row ml-auto mr-1 col-12'>

<a href="/create"><button class="btn btn-success text-white mb-1"> Make a group chat</button></a>

<form action="" class=" ml-auto w-75">
<div class="form-group shadow-lg">
  <input type="text" name="msg" id="" class="form-control" placeholder="Send a message" aria-describedby="helpId">
</div>
<button type="submit" class="btn btn-primary ml-auto shadow float-left">Send</button>
</form>
</div>
</div>
@endsection
