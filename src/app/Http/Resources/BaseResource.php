<?php

namespace App\Http\Resources;

use App\Enums\ResponseCode;
use App\Helpers\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource {
    public function with(Request $request): array {
        return APIResponse::meta(static::getResponseCode());
    }

    protected static function getResponseCode(): ResponseCode {
        return ResponseCode::SUCCESS;
    }
}
