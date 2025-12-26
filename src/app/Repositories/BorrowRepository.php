<?php

namespace App\Repositories;

use App\Data\PaginationQueryParamData;
use App\Models\BorrowsModel;
use App\Models\BorrowItemsModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BorrowRepository {
    public function fetchAll(PaginationQueryParamData $data): LengthAwarePaginator {
        return BorrowsModel::with(['items.book'])->paginate(
            perPage: $data->perPage,
            page: $data->page,
            total: $data->total
        )->withQueryString();
    }

    public function findById(int $id): ?BorrowsModel {
        return BorrowsModel::with(['items.book'])->find($id);
    }

    public function create(array $data): BorrowsModel {
        return BorrowsModel::create($data);
    }

    public function createBorrowItem(array $data): BorrowItemsModel {
        return BorrowItemsModel::create($data);
    }

    public function getBorrowsByMember(int $memberId): Collection {
        return BorrowsModel::where('member_id', $memberId)
            ->with(['items.book'])
            ->get();
    }
}

