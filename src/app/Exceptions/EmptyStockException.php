<?php

namespace App\Exceptions;

use App\Enums\ResponseCode;

final class EmptyStockException extends BaseException {
    public function __construct(
        string      $message = "Stok buku habis",
        int         $code = 400,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getAppErrorCode(): ResponseCode {
        return ResponseCode::EMPTY_STOCK;
    }
}
