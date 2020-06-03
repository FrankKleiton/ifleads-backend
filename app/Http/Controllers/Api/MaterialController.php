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
        $materials = Material::where([
            ['amout', '>', 1],
            ['returner_registration_mark', '=', null],
            ['tooker_registration_mark', '=', null]
        ])->get();

        return response()->json($materials);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'string|required',
            'description' => 'string|required'
        ]);

        $material = Material::create($validatedData);
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

        $validatedData = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'amount' => 'numeric'
        ]);

        $material->update($validatedData);

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
