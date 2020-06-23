<?php
$chatname = 'hoi';

$lastmsg = 'hoei';
?>

@extends('layouts.app')

@section('content')
<div class='col-12 h-100 w-100 mx-auto bg-dark text-light position-absolute row'>

<!--Main overview of chats for user -->

<div class='col-3 h-75 mt-2 overflow-auto'>
<?php // foreach ($chatvar as $row){ ?>
    <div class="card bg-secondary shadow-lg">
    <img class="card-img-top" src="" alt="">
    <div class="card-body">
        <h4 class="card-title">To: <?= $chatname ?> </h4>
        <p class="card-text"><?= $lastmsg ?></p>
    </div>
</div>
<?php // } ?>
</div>

<div class="mr-auto col-9 h-75 bg-secondary rounded mt-2 shadow-lg">
<?php // foreach($chatmsg as row) { ?>
<div class="card bg-dark shadow-lg mt-1">
    <img class="card-img-top" src="" alt="">
    <div class="card-body p-1">
        <h5 class="card-text text-white">User 1:</h5>
        <h5 class="card-text text-white"><?= $lastmsg ?></h5>
        <small class='text-secondary'>At [time and date]</small>
    </div>
</div>
<?php // } ?>
</div>
<div class='row ml-auto mr-1 w-75'>

<form action="" class=" w-100">
<div class="form-group shadow-lg">
  <input type="text" name="msg" id="" class="form-control" placeholder="" aria-describedby="helpId">
</div>
<button type="submit" class="btn btn-primary ml-auto shadow float-left">Send</button>
</form>
</div>
</div>
@endsection
