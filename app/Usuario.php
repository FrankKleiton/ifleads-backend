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
     * user on the basis of perception that change
     * laravel naming standard without defining that
     * change in table attribute will cause errors.
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
