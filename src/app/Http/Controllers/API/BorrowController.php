<?php

namespace App\Http\Controllers\API;

use App\Actions\Borrow\BorrowBooksAction;
use App\Actions\Borrow\FetchAllBorrowsAction;
use App\Actions\Borrow\FetchBorrowByIdAction;
use App\Data\BorrowBookData;
use App\Data\PaginationQueryParamData;
use App\Exceptions\NotFoundException;
use App\Helpers\APIResponse;
use App\Http\FormRequests\BorrowRequest;
use App\Http\Resources\BorrowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @tags Borrowing
 */
final class BorrowController {
    public function __construct(
        private readonly BorrowBooksAction     $borrowBooksAction,
        private readonly FetchAllBorrowsAction $fetchAll,
        private readonly FetchBorrowByIdAction $fetchById
    ) {
    }

    /**
     * Get all borrows
     *
     * Retrieve a list of all borrow records with member and book information.
     */
    public function fetchAll(Request $request): AnonymousResourceCollection {
        $data    = PaginationQueryParamData::fromRequest($request);
        $borrows = $this->fetchAll->execute($data);

        return BorrowResource::collection($borrows)->additional(APIResponse::meta());
    }

    /**
     * Get borrow by ID
     *
     * Retrieve detailed information about a specific borrow transaction.
     *
     * @response 404 {"message": "Borrow record not found"}
     * @throws NotFoundException
     */
    public function fetchById(int $id): BorrowResource {
        $borrow = $this->fetchById->execute($id);

        return BorrowResource::make($borrow);
    }

    /**
     * Borrow books
     *
     * Create a new borrow transaction for multiple books. This will automatically:
     * - Validate that all books exist
     * - Check if sufficient stock is available
     * - Create the borrow record
     * - Decrease the stock quantities
     * All operations are wrapped in a database transaction for safety.
     *
     * @bodyParam member_id integer required The ID of the member borrowing the books. Example: 1
     * @bodyParam books array required Array of books to borrow.
     * @bodyParam books[].book_id integer required The ID of the book to borrow. Example: 1
     * @bodyParam books[].quantity integer required The quantity to borrow. Example: 2
     *
     * @response 400 {"message": "Insufficient stock for book: The Great Gatsby"}
     * @response 404 {"message": "Book with ID 999 not found"}
     * @throws \Throwable
     */
    public function borrow(BorrowRequest $request): BorrowResource {
        $data   = BorrowBookData::fromRequest($request);
        $borrow = $this->borrowBooksAction->execute($data);

        return BorrowResource::make($borrow);
    }
}
