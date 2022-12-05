<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait ApiResponses
{
    /**
     * @param $data
     * @param array $headers
     * @param int $options
     * @return JsonResponse
     */
    protected function successfulResponse($data, array $headers = [], int $options = 0): JsonResponse
    {
        return response()->json(['success' => true, 'payload' => $data], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * @param $message
     * @param $code
     *
     * @return JsonResponse
     */
    protected function errorResponse($message, $code): JsonResponse
    {
        Log::warning("HTTP ERROR ${code}", ['message' => $message]);
        return response()->json(['success' => false, 'message' => $message], $code);
    }
}
