<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Material;

class LostMaterialController extends Controller
{
    public function index(Request $request)
    {
        $returned = $request->query('returned');
        $materials = null;

        if ((is_null($returned)) || ($returned !== "true" && $returned !== "false")) {
            $materials = Material::all();
        } else {
            $materials = Material::when($returned, function ($query, $returned) {
                            return ($returned === "true")
                                ? $query->whereNotNull('tooker_registration_mark')
                                : $query->whereNotNull('returner_registration_mark')
                                        ->whereNull('tooker_registration_mark');
                        })
                        ->get();
        }

        return response()->json($materials);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'string|required',
            'description' => 'string|required',
            'returner_registration_mark' => 'string|required|max:60'
        ]);

        $material = Material::create($validatedData);

        return response()->json($material, 201);
    }

    public function update(Request $request, $id)
    {
        $lostMaterial = Material::find($id);

        if (!$lostMaterial) {
            return response()->json([
                'status' => 'fail',
                'message' => "Lost Material doesn't exists"
            ], 404);
        }

        $validatedData = $request->validate([
            'tooker_registration_mark' => 'string|required|max:60'
        ]);

        $lostMaterial->tooker_registration_mark =
            $validatedData['tooker_registration_mark'];

        $lostMaterial->save();

        return response()->json();
    }
}
