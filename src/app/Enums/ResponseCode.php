<?php

namespace App\Enums;

enum ResponseCode: string {
    case SUCCESS = "ZYD-200";
    case CREATED = "ZYD-201";
    case BAD_REQUEST = "ZYD-ERR-400";
    case UNAUTHORIZED = "ZYD-ERR-401";
    case FORBIDDEN = "ZYD-ERR-403";
    case NOT_FOUND = "ZYD-ERR-404";
    case INTERNAL_SERVER_ERROR = "ZYD-ERR-500";
    case EMPTY_STOCK = "ZYD-ERR-001";
}
