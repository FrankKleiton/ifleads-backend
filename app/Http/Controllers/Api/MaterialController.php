<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Material;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::all();
        return response()->json($materials);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->only(['nome', 'descricao', 'usuario_id']);
            $material = Material::create($data);
            return response()->json($material, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid datas'
            ], 400);
        }

    }

    public function show($id)
    {
        $material = Material::find($id);
        return response()->json($material);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
