<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Material;
use App\Http\Requests\StoreLostMaterial;
use App\Http\Requests\UpdateLostMaterial;

class LostMaterialController extends Controller
{
    private Material $material;

    public function __construct(Material $material)
    {
        $this->material = $material;
    }

    public function index(Request $request)
    {
        $filter = $request->query('returned');
        $materials = null;

        if ($this->isValidFilter($filter)) {
            $materials = $this->material::all();
        } else {
            $materials = $this->material->filter($filter);
        }

        return response()->json($materials);
    }

    private function isValidFilter(?string $filter)
    {
        return (! $filter) || ($filter !== "true" && $filter !== "false");
    }

    public function store(StoreLostMaterial $request)
    {
        $material = $this->material::create($request->validated());

        return response()->json($material, 201);
    }

    public function update(UpdateLostMaterial $request, Material $material)
    {
        $material->update($request->validated());

        return response()->json();
    }
}
