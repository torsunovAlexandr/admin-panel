<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'login' => 'required|string'
        ]);

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

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login' => 'required|string'
        ]);

        $user = User::whereLogin($request->login)->first();

        if (!$user) {
            return response()->json(['message' => 'Неверный логин'], 401);
        }

        return response()->json([
            'message' => 'Вы успешно авторизированны'
        ]);
    }
}

