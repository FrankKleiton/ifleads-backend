<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterEmployee;
use App\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(RegisterEmployee $request)
    {
        $signup_informations = $request->validated();

        $user = User::create([
            'nome' => $signup_informations['nome'],
            'email' => $signup_informations['email'],
            'senha' => Hash::make($signup_informations['senha']),
            'role' => $signup_informations['role'],
        ]);

        return response()->json($user);
    }
}
