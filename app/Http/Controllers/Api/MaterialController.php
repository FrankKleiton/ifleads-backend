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
            ['amount', '>=', 1],
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

        $material = Material::where([
            ['name', '=', $validatedData['name']],
            ['returner_registration_mark', '=', null]
        ])->first();

        // I make that way because in the way that we structure the database,
        // It's possible to have a Lost Material and a Material with the same
        // name.
        if (! is_null($material)) {
            return response()->json([
                'status' => 'fail',
                'message' => sprintf('The %s already exists. Insert a valid material, please.', $material->name)
            ], 400);
        }

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
