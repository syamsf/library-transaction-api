<?php

namespace App\Http\Middleware;

use App\Helpers\IsUIPaths;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ForceJsonResponse {
    public function handle(Request $request, Closure $next): Response {
        if (IsUIPaths::validate($request)) {
            return $next($request);
        }

        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
