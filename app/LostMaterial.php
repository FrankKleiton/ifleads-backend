<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LostMaterial extends Model
{
    protected $table = 'materiais_perdidos';
    public $timestamps = false;
    protected $fillable = [
        'matriculaDeQuemEntregou'
    ];

    public function material()
    {
        return $this->belongsTo('App\Material', 'material_id', 'id');
    }
}
