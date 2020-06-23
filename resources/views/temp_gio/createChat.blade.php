<?php
if (isset($users)) $users = (array)$users;
$admin = [auth()->id()];
//var_dump($users);
?>

@extends('layouts.app')

@section('content')

    <div class="container text-white">
        <h2>Create chat</h2>
        <form action="{{ route('chat.create') }}" method="POST">
            @csrf

            <input type="hidden" name="admins" value="<?= json_encode($admin) ?>">

            <input type="checkbox" name="isgroup" id="isgroup">
            <label for="isgroup">Group</label>

            <br>

            {{-- V Als het een groep is V --}}
            <label for="group_name">Group name</label>
            <input type="text" name="group_name" id="group_name">

            <br>

            <label for="users">Select people to add</label> <br>
            <select multiple name="users" id="users">
                <?php foreach ($users as $user) {
                $user = (array)$user; ?>
                <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                <?php } ?>
            </select>

{{--            <br>--}}
{{--            --}}
{{--            --}}{{-- V Als het een private chat is V --}}
{{--            <label for="users">Select people to add</label> <br>--}}
{{--            <select name="users" id="users">--}}
{{--                <?php foreach ($users as $user) {--}}
{{--                $user = (array)$user; ?>--}}
{{--                <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>--}}
{{--                <?php } ?>--}}
{{--            </select>--}}

            <br>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>

@endsection
