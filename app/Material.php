<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materiais';
    public $timestamps = false;
    protected $fillable = [
        'nome',
        'descricao',
        'usuario_id'
    ];

}
