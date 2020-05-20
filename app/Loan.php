<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    /**
     * Define the mass assignable attributes
     *
     * @var array
     */
    protected $fillable = ['tooker_id', 'loan_time', 'loaned'];

    /**
     * Define if the model's table will have
     * created_at and updated_at columns.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Get the loan's user.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get the loaned material.
     */
    public function material()
    {
        return $this->belongsTo('App\Material');
    }
}
