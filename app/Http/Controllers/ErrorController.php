<?php

namespace App\Http\Controllers;

class ErrorController extends Controller
{
    public function unauthorized()
    {
        return response()->json([
            'error' => 'Token should be provide'
        ], 401);
    }

    public function forbidden()
    {
        return response()->json([
            'error' => "You don't have authorization to perform this action"
        ], 403);
    }
}
