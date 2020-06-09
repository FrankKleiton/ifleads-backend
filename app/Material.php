<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    /**
     * Define the mass assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'amount',
        'returner_registration_mark'
    ];

    /**
     * Hidde fields on the serialization.
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];

    /**
     * Define if the model's table will have
     * created_at and updated_at columns.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Get the user's loans.
     */
    public function loans()
    {
        return $this->hasMany('App\Loan');
    }

    public function isLost()
    {
        return !is_null($this->returner_registration_mark);
    }
}
