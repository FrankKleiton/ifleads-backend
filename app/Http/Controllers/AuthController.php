<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationFormRequest;
use App\Usuario;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegistrationFormRequest $request)
    {
        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'success' => true,
            'user_data' => [
                'nome' => $usuario->nome,
                'email' => $usuario->email,
                'role' => $usuario->role,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $token = null;

        try {
            if (!$token = auth()->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid information provided',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json(['success' => true, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
        ]);

        try {
            auth()->logout();
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out',
            ], 500);
        }

    }
}
