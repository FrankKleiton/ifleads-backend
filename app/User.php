<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    protected $guarded = [];
    protected $table = 'usuarios';
    protected $hidden = ['senha'];
    public $timestamps = false;

    /**
     * The booted method of the model
     *
     * @return void
     */
    public static function booted()
    {
        static::creating(function ($user) {
            $user->senha = Hash::make($user->senha);
        });
    }

    /**
     * Checks whether the user is an admin.
     *
     * If the user has the role 1, he will be
     * treated as a admin by the application.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return intval($this->role) === 1;
    }
}
