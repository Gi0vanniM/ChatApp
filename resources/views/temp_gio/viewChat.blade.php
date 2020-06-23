<?php
if (isset($id)) $id = $id;
if (isset($messages)) $messages = $messages;
?>

@extends('layouts.app')

@section('content')

    <div class="container">

        <div id="messages">
            <ul>
                <?php foreach ($messages as $message) { ?>
                <li><strong><?= $message['userid'] ?></strong><?= $message['message'] ?></li>
                <?php } ?>
            </ul>
        </div>

        <form action="{{ route("chat.id", ['id'=>$id]) }}" method="POST" class="form">
            @csrf
            <div class="row">
                <input class="form-control-lg" type="text">
                <button type="submit" class="btn btn-lg btn-primary">Send</button>
            </div>
        </form>

    </div>

@endsection
