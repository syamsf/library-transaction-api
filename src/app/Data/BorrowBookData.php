<?php

namespace App\Data;

use App\Http\FormRequests\BorrowRequest;

final class BorrowBookData {
    public function __construct(
        public readonly int   $memberId,
        public readonly array $books,
    ) {
    }

    public static function fromRequest(BorrowRequest $request): self {
        return new self(
            memberId: $request->input('member_id'),
            books: array_map(
                fn(array $item) => new BorrowBookItemsData(
                    bookId: $item['book_id'],
                    quantity: $item['quantity'],
                ),
                $request->input('books', []),
            ),
        );
    }
}
