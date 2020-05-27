<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /**
     * Define the mass assignable attributes
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Hidde fields on the serialization.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * Define if the model's table will have
     * created_at and updated_at columns.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The booted method of the model
     *
     * @return void
     */
    public static function booted()
    {
        static::creating(function ($user) {
            $user->password = Hash::make($user->password);
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
        return $this->role === 'admin';
    }

    /**
     * Get the loans made by the user.
     */
    public function loans()
    {
        return $this->hasMany('App\Loan', 'user_id');
    }

}
