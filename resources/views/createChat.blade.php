<?php
if (isset($users)) $users = (array)$users;
$admin = [auth()->id()];
?>

@extends('layouts.app')

@section('content')

    <div class="container text-white text-center rounded bg-dark border border-secondary shadow-lg col-5 mt-5">
        <h1>Create chat</h1>
        <form action="{{ route('chat.create') }}" method="POST">
            @csrf

            <input type="hidden" name="admins[]" value="<?= $admin[0] ?>">

            <input type="hidden" value="true" {{--type="checkbox"--}} name="isgroup" id="isgroup">
            {{--            <label for="isgroup">Group</label>--}}

            <br>

            {{-- V Als het een groep is V --}}
            <h5 class="mb-0">Group name</h5> <br>
            <input type="text" name="group_name" id="group_name" class="rounded bg-dark text-white"
                   placeholder="E.g. The cooler kids club" required>

            <br>

            <label for="users" class="mt-5">Select people to add</label> <br>
            <select multiple name="users[]" id="users"
                    class="custom-select col-8 mt-3 bg-dark border border-secondary rounded shadow-lg text-white text-center"
                    required>
                <?php foreach ($users as $user) {
                $user = (array)$user;
                if ($user['id'] != $admin[0]) {?>
                <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                <?php }} ?>
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
            <button type="submit" class="btn btn-success mt-5 mb-5 col-3 shadow-lg">Create</button>
        </form>
    </div>

@endsection
