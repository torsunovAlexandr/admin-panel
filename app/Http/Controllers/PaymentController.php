<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Получение списка платежей с пагинацией и поиском
     */
    public function index(Request $request): JsonResponse
    {
        $query = Payment::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                    ->orWhere('login', 'like', "%$search%")
                    ->orWhere('details', 'like', "%$search%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $payments = $query->paginate($perPage);

        return response()->json($payments);
    }

    /**
     * Cоздание нового платежа
     */
    public function store(Request $request): JsonResponse
    {
        $messages = [
            'login.exists' => 'Пользователь не найден.',
        ];

        $validator = Validator::make($request->all(), [
            'id' => 'required|string|unique:payments',
            'login' => 'required|string|exists:users,login',
            'details' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'status' => 'required|in:CREATED,PAID',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = Payment::create($request->all());
        return response()->json($payment, 201);
    }

    /**
     * Обновить статус платежа
     */
    public function updateStatus(Request $request): JsonResponse
    {
        $messages = [
            'id.exists' => 'Пользователь не найден.',
            'status.in' => 'Передан некорректный статус',
        ];

        $validator = Validator::make($request->all(), [
            'id' => 'required|string|exists:payments,id',
            'status' => 'required|in:CREATED,PAID',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = Payment::find($request->input('id'));
        $payment->status = $request->input('status');
        $payment->save();

        // Если статус изменился на PAID, обновляем баланс пользователя
        if ($payment->status === 'PAID') {
            $user = $payment->user;
            if ($user) {
                $user->balance += $payment->amount;
                $user->save();
            }
        }

        // На случай возврата денег например
        if ($payment->status === 'CREATED') {
            $user = $payment->user;
            if ($user) {
                $user->balance -= $payment->amount;
                $user->save();
            }
        }

        return response()->json($payment);
    }
}
