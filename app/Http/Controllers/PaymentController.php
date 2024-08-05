<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentStatusRequest;
use App\Http\Requests\SearchPaymentRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Получение списка платежей с пагинацией и поиском
     */
    public function index(SearchPaymentRequest $request): JsonResponse
    {
        $query = Payment::query();

        if ($request->filled('search')) {
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
     * Создание нового платежа
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = Payment::create($request->validated());
        return response()->json($payment, 201);
    }

    /**
     * Обновить статус платежа
     */
    public function updateStatus(UpdatePaymentStatusRequest $request): JsonResponse
    {
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
