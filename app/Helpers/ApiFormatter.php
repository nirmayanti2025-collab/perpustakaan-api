<?php

namespace App\Helpers;

class ApiFormatter
{
    protected static $response = [
        'code'   => null,
        'message' => null,
        'data' => [],
    ];

    public static function createJson ($code, $message, $data = []) {
        self::$response['code'] = $code;
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        // Return array payload only; controllers should wrap with response()->json(..., $code)
        return self::$response;
    }
    public static function filterSensitiveData(array $data = []): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'token',
            'api_key',
            'secret'
        ];

        foreach ($data as $key => $value) {

            // Jika key sensitif
            if (in_array($key, $sensitiveFields)) {
                $data[$key] = '[FILTERED]';
            }

            // Jika value berupa array, lakukan filtering ulang (rekursif)
            if (is_array($value)) {
                $data[$key] = self::filterSensitiveData($value);
            }
        }

        return $data;
    }
}