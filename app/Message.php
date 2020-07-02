<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    //
    protected $fillable = ['message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
