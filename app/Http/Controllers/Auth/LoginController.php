<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginEmployee;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginEmployee $request)
    {
        $credentials = $request->validated();

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['status' => 'fail'], 401);
        }
        return response()->json(['token' => $token]);
    }
}
