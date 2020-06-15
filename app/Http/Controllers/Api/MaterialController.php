<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Material;
use App\Http\Requests\StoreMaterial;
use App\Http\Requests\UpdateMaterial;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::borrowable()->get();

        return response()->json($materials);
    }

    public function store(StoreMaterial $request)
    {
        $info = $request->validated();

        $hasBorrowable = Material::hasBorrowableWithName($info['name']);

        if ($hasBorrowable) {
            return response()->json([
                'status' => 'fail',
                'message' => __('response.duplicated_material')
            ], 400);
        }

        $material = Material::create($info);
        return response()->json($material, 201);
    }

    public function show(Material $material)
    {
        return response()->json($material);
    }

    public function update(UpdateMaterial $request, Material $material)
    {
        $material->update($request->validated());

        return response()->json($material);
    }

    public function destroy(Material $material)
    {
        $material->delete();
    }
}
