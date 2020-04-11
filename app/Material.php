<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    protected $table = 'materiais';
    public $timestamps = false;
    protected $fillable = [
        'nome',
        'descricao'
    ];

}
