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

        $materials = Material::where(
            'returner_registration_mark', '!=', null
        )->get();

        if ($returned === "true") {
            $materials = $materials->filter(function ($material) {
                return ! is_null($material->tooker_registration_mark);
            });
        } else {
            if ($returned === "false") {
                $materials = $materials->filter(function ($material) {
                    return is_null($material->tooker_registration_mark);
                });
            }
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
}
