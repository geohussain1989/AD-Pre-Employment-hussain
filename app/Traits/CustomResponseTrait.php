<?php

namespace App\Traits;

trait CustomResponseTrait
{
    public function sendResponse($statusCode = 200, $data = [], $message = '', array $errors = [], $vErrors = [], $customErrorCode = null)
    {
        $status = ($statusCode === 200 || $statusCode === 201) ? true : false;
        $data = !empty($data) ? $data : [];
        $errors = !empty($errors) ? [
            'custom_code' => $customErrorCode ? $customErrorCode : $statusCode,
            'messages' => $errors
        ] : [];

        $return = [
            'status' => $status,
            'code' => $statusCode,
            'custom_code' => $customErrorCode ? $customErrorCode : $statusCode,
            'data' => $data,
            'message' => $message,
            'error' => $errors,
            'errors' => $vErrors
        ];

        return response()->json($return, $statusCode, [], JSON_UNESCAPED_SLASHES);
    }
}
