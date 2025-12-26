<?php

namespace App\Helpers;

use Illuminate\Http\Request;

final class IsUIPaths {
    public static function validate(Request $request): bool {
        $uiPaths = [
            'horizon*',
            'telescope*',
            'sanctum/csrf-cookie',
        ];

        return $request->is($uiPaths);
    }
}
