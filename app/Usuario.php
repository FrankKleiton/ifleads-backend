<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'nome', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Defining table name due to the use of
     * the portuguese translation of the word
     * user for avoid possible errors in laravel
     * table name transformations.
     * 
     * @var string
     */
    protected $table = 'usuarios';
    
    public $timestamps = false;
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
