<?php

namespace App\Exceptions;

use App\Enums\ResponseCode;

abstract class BaseException extends \Exception {
    public function __construct(
        string      $message = "An error occurred.",
        int         $code = 500,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getAppErrorCode(): ResponseCode {
        return ResponseCode::INTERNAL_SERVER_ERROR;
    }
}
