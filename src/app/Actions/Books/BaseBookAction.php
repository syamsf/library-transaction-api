<?php

namespace App\Actions\Books;

use App\Repositories\BookRepository;

abstract class BaseBookAction {
    public function __construct(
        protected readonly BookRepository $bookRepository
    ) {
    }
}
