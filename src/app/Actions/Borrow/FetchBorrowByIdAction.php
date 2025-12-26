<?php

namespace App\Actions\Borrow;

use App\Exceptions\NotFoundException;
use App\Models\BorrowsModel;

final class FetchBorrowByIdAction extends BaseBorrowAction {
    /**
     * @throws NotFoundException
     */
    public function execute(int $id): BorrowsModel {
        $borrow = $this->borrowRepository->findById($id);

        if (empty($borrow)) {
            throw new NotFoundException("Borrow record not found");
        }

        return $borrow;
    }
}

