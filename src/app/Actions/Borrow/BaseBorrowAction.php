<?php

namespace App\Actions\Borrow;

use App\Repositories\BookRepository;
use App\Repositories\BorrowRepository;

abstract class BaseBorrowAction {
    public function __construct(
        protected readonly BookRepository   $bookRepository,
        protected readonly BorrowRepository $borrowRepository
    ) {
    }
}
