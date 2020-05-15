<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Material;

class LostMaterialController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = (object) $request->validate([
            'nome' => 'string|required',
            'descricao' => 'string|required',
            'matriculaDeQuemEntregou' => 'string|required|max:20'
        ]);

        $material = Material::create([
            'nome' => $validatedData->nome,
            'descricao' => $validatedData->descricao
        ]);

        $lostMaterial = $material->lostMaterial()->create([
            'matriculaDeQuemEntregou' => $validatedData->matriculaDeQuemEntregou
        ]);

        return response()->json([
            'id' => $material->id,
            'nome' => $material->nome,
            'descricao' => $material->descricao,
            'matriculaDeQuemEntregou' => $lostMaterial->matriculaDeQuemEntregou
        ], 201);
    }
}
