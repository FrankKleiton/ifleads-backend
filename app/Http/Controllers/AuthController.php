<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegistrationFormRequest;
use App\Usuario;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(RegistrationFormRequest $request)
    {
        $usuario = new Usuario();
        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->role = $request->role;
        $usuario->save();

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
        $input = $request->only(['email', 'password']);
        $token = null;

        try {
            if (!$token = JWTAuth::attempt($input)) {
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
}
