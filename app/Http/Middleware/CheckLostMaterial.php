<?php

namespace App\Http\Middleware;

use Closure;
use App\Material;

class CheckLostMaterial
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $material = Material::findOrFail($request->input('material_id'));

            $isLoanable = $material->lostMaterial()->get()->isEmpty();

            if (!$isLoanable) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Ask for a existing material'
                ], 404);
            }

            return $next($request);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Insert a valid material_id'
            ], 422);
        }
    }
}
