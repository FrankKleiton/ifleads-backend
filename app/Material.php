<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    /**
     * Define a custom name for the database
     * table.
     *
     * @var string
     */
    protected $table = 'materiais';

    /**
     * Define if the model's table will have
     * created_at and updated_at columns.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Define the mass assignable attributes
     *
     * @var array
     */
    protected $fillable = ['nome', 'descricao'];

    /**
     * Hidde fields on the serialization.
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];

    /**
     * Get the user's loans.
     */
    public function loans()
    {
        return $this->hasMany('App\Loan');
    }

    public function isLost($state = null)
    {
        return !is_null($state);
    }
}
