<?php

namespace App\Actions\Books;

use App\Exceptions\NotFoundException;

final class DeleteBookAction extends BaseBookAction {
    /**
     * @throws NotFoundException
     */
    public function execute(int $id): bool {
        $book = $this->bookRepository->findById($id);

        if (empty($book)) {
            throw new NotFoundException("Book not found");
        }

        return $this->bookRepository->delete($id);
    }
}

