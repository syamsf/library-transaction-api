<?php

namespace App\Actions\Books;

use App\Data\UpsertBookData;
use App\Exceptions\NotFoundException;
use App\Models\BooksModel;

final class UpdateBookAction extends BaseBookAction {
    /**
     * @throws NotFoundException
     */
    public function execute(int $id, UpsertBookData $data): BooksModel {
        $book = $this->bookRepository->findById($id);

        if (empty($book)) {
            throw new NotFoundException("Book not found");
        }

        $this->bookRepository->update($id, $data->toUpdate());

        return $this->bookRepository->findById($id);
    }
}

