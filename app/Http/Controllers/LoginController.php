<?php

namespace App\Http\Controllers;

use App\Exceptions\LoginException;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->username)->orWhere("name", $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            abort(Response::HTTP_UNAUTHORIZED, "The provided credentials are incorrect.");
        }

        return $user->createToken($user->name)->plainTextToken;
    }

    public function logout(Request $request)
    {
        return $request->user()->tokens()->delete();
    }
}
