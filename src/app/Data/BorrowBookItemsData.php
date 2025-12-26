<?php

namespace App\Data;

final class BorrowBookItemsData {
    public function __construct(
        public readonly int $bookId,
        public readonly int $quantity,
    ) {
    }
}
