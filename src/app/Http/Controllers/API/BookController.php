<?php

namespace App\Http\Controllers\API;

use App\Actions\Books\CreateBookAction;
use App\Actions\Books\FetchAllBooksAction;
use App\Actions\Books\FetchBookByIdAction;
use App\Actions\Books\UpdateBookAction;
use App\Actions\Books\DeleteBookAction;
use App\Data\PaginationQueryParamData;
use App\Data\UpsertBookData;
use App\Exceptions\NotFoundException;
use App\Helpers\APIResponse;
use App\Http\FormRequests\CreateBookRequest;
use App\Http\FormRequests\UpdateBookRequest;
use App\Http\Resources\BooksResource;
use App\OpenAPI\PaginatedResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @tags Books
 */
final class BookController {
    public function __construct(
        private readonly FetchAllBooksAction $getAllBooksAction,
        private readonly FetchBookByIdAction $getBookByIdAction,
        private readonly CreateBookAction    $createBookAction,
        private readonly UpdateBookAction    $updateBookAction,
        private readonly DeleteBookAction    $deleteBookAction
    ) {
    }

    /**
     * Get all books
     *
     * Retrieve a list of books.
     */
    public function fetchAll(Request $request): AnonymousResourceCollection {
        $data  = PaginationQueryParamData::fromRequest($request);
        $books = $this->getAllBooksAction->execute($data);

        return BooksResource::collection($books)->additional(APIResponse::meta());
    }

    /**
     * Get book by ID
     *
     * Retrieve detailed information about a specific book.
     *
     * @response 404 {"message": "Book not found"}
     * @throws NotFoundException
     */
    public function fetchById(int $id): JsonResponse {
        $book = $this->getBookByIdAction->execute($id);

        return BooksResource::make($book);
    }

    /**
     * Create new book
     *
     * Add a new book to the library inventory.
     *
     * @bodyParam title string required The title of the book. Example: Clean Code
     * @bodyParam author string required The author of the book. Example: Robert C. Martin
     * @bodyParam stock integer The initial stock quantity. Example: 10
     */
    public function create(CreateBookRequest $request): JsonResponse {
        $data = UpsertBookData::fromRequest($request);
        $book = $this->createBookAction->execute($data);

        return BooksResource::make($book);
    }

    /**
     * Update book
     *
     * Update an existing book's information. All fields are optional.
     *
     * @bodyParam title string The new title of the book. Example: Clean Code - Second Edition
     * @bodyParam author string The new author of the book. Example: Robert C. Martin
     * @bodyParam stock integer The new stock quantity. Example: 15
     *
     * @response 404 {"message": "Book not found"}
     * @throws NotFoundException
     */
    public function update(UpdateBookRequest $request, int $id): JsonResponse {
        $data = UpsertBookData::fromRequest($request);

        $book = $this->updateBookAction->execute($id, $data);

        return BooksResource::make($book);
    }

    /**
     * Delete book
     *
     * Soft delete a book from the library. The book will be marked as deleted but retained in the database.
     *
     * @response 404 {"message": "Book not found"}
     * @throws NotFoundException
     */
    public function destroy(int $id): JsonResponse {
        $this->deleteBookAction->execute($id);

        return BooksResource::make();
    }
}

