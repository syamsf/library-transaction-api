<?php

namespace App\Actions\Borrow;

use App\Data\BorrowBookData;
use App\Data\BorrowBookItemsData;
use App\Exceptions\EmptyStockException;
use App\Exceptions\NotFoundException;
use App\Models\BooksModel;
use App\Models\BorrowsModel;
use Illuminate\Support\Facades\DB;

final class BorrowBooksAction extends BaseBorrowAction
{
    /**
     * Pada proses peminjaman buku ini, masalah yang mungkin terjadi adalah:
     * - Buku yang dipinjam tidak ditemukan
     * - Stok buku tidak mencukupi
     * - Race condition saat mengurangi stok buku
     * - Buku yang dipinjam bisa jadi duplikat pada request
     *
     * Solusi yang diterapkan:
     * 1. Buku yang dipinjam bisa jadi duplikat pada request
     *  - Filter data buku yang unik berdasarkan bookId sebelum memprosesnya
     *
     * 2. Buku yang dipinjam tidak ditemukan
     *  - Cek apakah buku ada dengan whereIn()
     *
     * 3. Stok buku tidak mencukupi
     *  - Cek stok buku sebelum melakukan peminjaman
     *
     * 4. Race condition saat mengurangi stok buku
     * - Menggunakan database transaction untuk memastikan operasi atomic
     * - Menggunakan SELECT FOR UPDATE untuk mengunci row record yang akan diupdate sehingga memastikan hanya satu operasi yang berjalan (Pessimistic Locking)
     * - Tidak menggunakan mutex lock karena ada kemungkinan app instance akan lebih dari 1 ketika dilakukan horizontal scaling sehingga lock tidak akan efektif.
     * Lock juga hanya terjadi di sisi aplikasi, tapi tidak di sisi database.
     * - Sama halnya dengan Redis lock, lock hanya terjadi di sisi aplikasi, tapi tidak di sisi database.
     * @throws \Throwable
     */
    public function execute(BorrowBookData $data): BorrowsModel {
        return DB::transaction(function () use ($data) {
            // 1. Filter unique book IDs from the request
            $borrowItems = collect($data->books)->sortBy("bookId")->unique("bookId");
            $bookIdList  = $borrowItems->pluck('bookId')->all();

            // 2. Fetch books for update to lock the rows
            $books = $this->bookRepository->findByIdsForUpdate($bookIdList)->keyBy('id');

            // 3. Validate each book's existence and stock
            /** @var BorrowBookItemsData $bookData */
            foreach ($data->books as $bookData) {
                /** @var BooksModel $book */
                $book = $books->get($bookData->bookId);

                if (empty($book)) {
                    throw new NotFoundException("Book with ID {$bookData->bookId} not found");
                }

                if ($book->stock < $bookData->quantity) {
                    throw new EmptyStockException();
                }
            }

            // 4. Create borrow record
            $borrow = $this->borrowRepository->create([
                'member_id' => $data->memberId,
                'borrowed_at' => now(),
            ]);

            // 5. Create items and decrease stock
            /** @var BorrowBookItemsData $bookData */
            foreach ($data->books as $bookData) {
                $this->borrowRepository->createBorrowItem([
                    'borrow_id' => $borrow->id,
                    'book_id'   => $bookData->bookId,
                    'quantity'  => $bookData->quantity,
                ]);

                $updatedRow = $this->bookRepository->decreaseStock(
                    $bookData->bookId,
                    $bookData->quantity
                );

                if ($updatedRow === false || $updatedRow === 0) {
                    throw new EmptyStockException();
                }
            }

            return $this->borrowRepository->findById($borrow->id);
        });
    }
}
