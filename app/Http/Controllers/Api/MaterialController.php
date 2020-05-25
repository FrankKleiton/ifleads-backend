<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Material;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::all();
        return response()->json($materials);
    }

    public function store(Request $request)
    {
        $data = $request->only(['nome', 'descricao']);

        $material = new Material;
        $material->fill($data);
        $material->user()->associate(Auth::user());
        $material->save();
        $material->makeHidden(['user']);
        return response()->json($material, 201);
    }

    public function show($id)
    {
        $material = Material::find($id);
        return response()->json($material);
    }

    public function update(Request $request, $id)
    {
        $material = Material::find($id);

        if(!$material) {
            return response()->json([
                 'error' => "Material doesn't exists"
            ], 400);
        }

        $material->nome = $request->has('nome')
            ? $request->input('nome')
            : $material->nome;
        $material->descricao = $request->has('descricao')
            ? $request->input('descricao')
            : $material->descricao;
        $material->save();

        return response()->json($material);

    }

    public function destroy($id)
    {
        $material = Material::find($id);
        if (!$material) {
            return response()->json([
                'error' => "Material doesn't exists"
            ], 400);
        }

        $material->delete();
    }
}
