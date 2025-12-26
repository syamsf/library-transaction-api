<?php

namespace App\Helpers;

use App\Enums\ResponseCode;
use Illuminate\Http\JsonResponse;

final class APIResponse {
    public static function success(
        array         $data = [],
        array         $meta = [],
        ?ResponseCode $responseCode = null,
        int           $statusCode = JsonResponse::HTTP_OK
    ): JsonResponse {
        $meta = array_merge($meta, [
            "status"        => "success",
            "response_code" => empty($responseCode) ? ResponseCode::SUCCESS->value : $responseCode->value,
        ]);

        return response()->json(["data" => $data, "meta" => $meta], $statusCode);
    }

    public static function meta(ResponseCode $responseCode = ResponseCode::SUCCESS): array {
        return [
            "meta" => [
                "status"        => "success",
                "response_code" => $responseCode->value
            ]
        ];
    }
}
