<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterEmployee;
use App\User;

class UserController extends Controller
{
    public function __invoke(RegisterEmployee $request)
    {
        $user = User::create($request->validated());
        return response()->json($user, 201);
    }
}
