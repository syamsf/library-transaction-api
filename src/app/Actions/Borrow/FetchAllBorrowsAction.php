<?php

namespace App\Actions\Borrow;

use App\Data\PaginationQueryParamData;
use Illuminate\Pagination\LengthAwarePaginator;

final class FetchAllBorrowsAction extends BaseBorrowAction {
    public function execute(PaginationQueryParamData $data): LengthAwarePaginator {
        return $this->borrowRepository->fetchAll($data);
    }
}

