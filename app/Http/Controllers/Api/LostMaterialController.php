<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Material;

class LostMaterialController extends Controller
{
    public function store(Request $request) {
        $materialData = $request->only(['nome', 'descricao']);
        $lostMaterialData = $request->only(['matriculaDeQuemEntregou']);

        $material = Material::create($materialData);
        $lostMaterial = $material->lostMaterial()->create($lostMaterialData);

        return response()->json([
            'id' => $material->id,
            'nome' => $material->nome,
            'descricao' => $material->descricao,
            'matriculaDeQuemEntregou' => $lostMaterial->matriculaDeQuemEntregou
        ], 201);
    }
}
