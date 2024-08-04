<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiSignature
{
    public function handle(Request $request, Closure $next)
    {
        //для get запросов не проверяем
        if ($request->isMethod('get')) {
            return $next($request);
        }

        $providedSignature = $request->header('X-Sign');
        if (!$providedSignature) {
            return response()->json(['error' => 'Ошибка при получении подписи'], 400);
        }

        $data = $request->except(['X-Sign']); // Убираем заголовок из данных для подписи
        $orderedData = $this->orderData($data);
        $orderedData['k'] = env('API_KEY');
        $jsonString = json_encode($orderedData);
        $cleanJsonString = str_replace('/\//g', '\\/', $jsonString);
        $calculatedSignature = hash_hmac('sha256', $cleanJsonString, env('API_SECRET'));

        if ($providedSignature !== $calculatedSignature) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        return $next($request);
    }

    private function orderData(array $data): array
    {
        // Удаляем вложенные объекты
        $filtered = array_filter($data, function ($value) {
            return !is_array($value);
        });

        ksort($filtered);

        return $filtered;
    }
}
