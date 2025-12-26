<?php

namespace App\Actions\Books;

use App\Data\UpsertBookData;
use App\Models\BooksModel;

final class CreateBookAction extends BaseBookAction {
    public function execute(UpsertBookData $data): BooksModel {
        return $this->bookRepository->create($data->toCreate());
    }
}

