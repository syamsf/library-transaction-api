<?php

namespace App\Actions\Books;

use App\Exceptions\NotFoundException;
use App\Models\BooksModel;

final class FetchBookByIdAction extends BaseBookAction {
    /**
     * @throws NotFoundException
     */
    public function execute(int $id): BooksModel {
        $book = $this->bookRepository->findById($id);

        if (empty($book)) {
            throw new NotFoundException("Book not found");
        }

        return $book;
    }
}

