<?php

namespace App\Actions\Books;

use App\Data\PaginationQueryParamData;
use Illuminate\Pagination\LengthAwarePaginator;

final class FetchAllBooksAction extends BaseBookAction {
    public function execute(PaginationQueryParamData $data): LengthAwarePaginator {
        return $this->bookRepository->fetchAll($data);
    }
}
