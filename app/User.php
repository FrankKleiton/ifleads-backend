<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    protected $table = 'usuarios';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = ['senha'];
}
