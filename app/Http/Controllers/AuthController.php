<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        if (User::whereLogin($request->login)->first() !== null) {
            return response()->json([
                'message' => 'Вы уже зарегистрированы',
            ]);
        }

        User::create([
            'login' => $request->login
        ]);

        return response()->json([
            'message' => 'Вы успешно зарегистрированы',
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::whereLogin($request->login)->first();

        if (!$user) {
            return response()->json(['message' => 'Неверный логин'], 401);
        }

        return response()->json([
            'message' => 'Вы успешно авторизированны'
        ]);
    }
}
