<?php

namespace App\Libraries;
class General
{
    public static function response($status, $data = null, $message = null, $error = null, $statusCode = null)
    {
        $resCode = $statusCode ?? ($status ? 200 : 400);

        $output = [
            'success' => $status,
            'message' => $message,
            'apiVersion' => config('app.apiVersion'),
            'error' => $error,
            'result' => $data,
        ];

        return response()->json($output, $resCode);
    }
}
