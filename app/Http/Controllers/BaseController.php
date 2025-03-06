<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    protected function successResponse($data, string $message, int $status = 200): JsonResponse
    {
        if ($data instanceof PaginationResource) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data->toArray(request())['data'],
                'pagination' => $data->toArray(request())['pagination'],
            ], $status);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function errorResponse(string $message, int $status = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
