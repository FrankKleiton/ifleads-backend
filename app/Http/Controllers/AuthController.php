<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationFormRequest;
use App\Usuario;

class AuthController extends Controller
{
    public function register(RegistrationFormRequest $request)
    {
        $usuario = new Usuario();
        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        $usuario->senha = bcrypt($request->senha);
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
}
