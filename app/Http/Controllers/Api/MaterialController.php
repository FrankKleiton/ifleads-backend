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
        //
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
