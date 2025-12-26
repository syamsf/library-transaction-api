<?php

namespace App\Repositories;

use App\Data\PaginationQueryParamData;
use App\Models\BooksModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BookRepository {
    public function fetchAll(PaginationQueryParamData $data): LengthAwarePaginator {
        return BooksModel::paginate($data->perPage, page: $data->page, total: $data->total)->withQueryString();
    }

    public function findById(int $id): ?BooksModel {
        return BooksModel::find($id);
    }

    public function create(array $data): BooksModel {
        return BooksModel::create($data);
    }

    public function update(int $id, array $data): bool {
        $book = $this->findById($id);
        if (!$book) {
            return false;
        }
        return $book->update($data);
    }

    public function delete(int $id): bool {
        $book = $this->findById($id);
        if (!$book) {
            return false;
        }
        return $book->delete();
    }

    public function decreaseStock(int $id, int $quantity): bool|int {
        return BooksModel::where('id', $id)
            ->where('stock', '>=', $quantity)
            ->decrement('stock', $quantity);
    }

    public function increaseStock(int $id, int $quantity): bool|int {
        return BooksModel::where('id', $id)->increment('stock', $quantity);
    }

    public function findByIdsForUpdate(array $ids): Collection {
        return BooksModel::whereIn('id', $ids)
            ->lockForUpdate()
            ->get();
    }
}

