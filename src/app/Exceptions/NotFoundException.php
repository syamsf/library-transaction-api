<?php

namespace App\Exceptions;

use App\Enums\ResponseCode;

final class NotFoundException extends BaseException {
    public function __construct(
        string      $message = "Resource not found.",
        int         $code = 404,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getAppErrorCode(): ResponseCode {
        return ResponseCode::NOT_FOUND;
    }
}
