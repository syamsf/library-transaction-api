<?php declare(strict_types = 1);

namespace App\Exceptions;

use App\Enums\ResponseCode;
use App\Helpers\IsUIPaths;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Context;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class JsonExceptionHandler {
    public function render($request, Throwable $e): bool|JsonResponse {
        if (IsUIPaths::validate($request)) {
            return false;
        }

        $errors         = null;
        $exceptionTrace = $e->getTrace();

        /**
         * Why there"s json_encode over here and there"s json_last_error() checks?
         *   Because there"s a recursion problem when Laravel"s response()->json() tries to encode the exception trace
         *   into JSON.
         *   The problem is "RECURSION DETECTED".
         *
         * Caused by:
         *   - Illegal character in error traces that tries to encode into JSON (Examples: AMQP error)
         */
        json_encode($e->getTrace());

        $errorCode = null;
        $traceId   = Context::get('traceId');

        switch (true) {
            case $e instanceof NotFoundHttpException:
                $e = $this->notFoundResponse($e);
                break;
            case $e instanceof ModelNotFoundException:
                $e = $this->notFoundModelResponse($e);
                break;
            case $e instanceof ValidationException:
                $errors    = $e->errors();
                $errorCode = 400;
                break;
            default:
                break;
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            $exceptionTrace = $e->getTraceAsString();
        }

        $response = [
            "message"          => $e->getMessage(),
            "ziyad_error_code" => $this->getAppErrorCode($e),
            "trace_id"         => $traceId,
        ];

        if (config("app.debug")) {
            $additionalResponse = [
                "file"    => $e->getFile(),
                "line"    => $e->getLine(),
                "code"    => $e->getCode(),
                "details" => $errors,
                "trace"   => $exceptionTrace,
            ];

            $response = array_merge($response, $additionalResponse);
        }

        return response()->json($response, is_null($errorCode) ? $this->getExceptionStatusCode($e) : $errorCode);
    }

    protected function getExceptionStatusCode(Throwable $exception): int {
        if (method_exists($exception, "getCode")) {
            $isErrorCodeInt = is_int($exception->getCode());
            $errorCode      = $isErrorCodeInt ? $exception->getCode() : 500;
            return $errorCode > 599 || $errorCode <= 99 ? 500 : $errorCode;
        }

        return 500;
    }

    protected function getAppErrorCode(Throwable $exception): ResponseCode {
        if (method_exists($exception, "getAppErrorCode")) {
            return $exception->getAppErrorCode();
        }

        return ResponseCode::INTERNAL_SERVER_ERROR;
    }

    private function notFoundResponse(Throwable $exception): \Throwable {
        return new NotFoundException($exception->getMessage(), $exception->getCode(), $exception);
    }

    private function notFoundModelResponse(Throwable $exception): \Throwable {
        $filteredModel = str_replace("No query results for model [App\\Models\\", "", $exception->getMessage());
        $filteredModel = str_replace("].", "", $filteredModel);
        return new \Exception("{$filteredModel} data not found", Response::HTTP_NOT_FOUND);
    }
}
