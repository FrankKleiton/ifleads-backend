<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = ['tooker_id'];

    /*
     * Get the loan's user.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /*
     * Get the loaned material.
     */
    public function material()
    {
        return $this->belongsTo('App\Material');
    }
}
