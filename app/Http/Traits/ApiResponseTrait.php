<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
    public function response ($status, $message, $code = 201, $data = null, $errors = null, $params = null)
    {
        return response()->json([
            'status' => [
                'code' => $code,
                'status' => $status,
                'message' => $message,
            ],
            'query' => [
                'method' => request()->method(),
                'params' => $params ?? request()->all()
            ],
            'data' => $data ?? [],
            'errors' => $errors ?? []
        ], $code);
    }
}
