<?php

namespace App\Data;

use Illuminate\Http\Request;

final class PaginationQueryParamData {
    public function __construct(
        public readonly ?int $perPage = null,
        public readonly ?int $page = null,
        public readonly ?int $total = null
    ) {
    }

    public static function fromRequest(Request $request): self {
        return new self(
            perPage: $request->query('perPage'),
            page: $request->query('page'),
            total: $request->query('total'),
        );
    }
}
