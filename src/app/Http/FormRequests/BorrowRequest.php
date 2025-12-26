<?php

namespace App\Http\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

final class BorrowRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'member_id'        => 'required|integer|exists:users,id',
            'books'            => 'required|array|min:1',
            'books.*.book_id'  => 'required|integer|exists:books,id',
            'books.*.quantity' => 'required|integer|min:1',
        ];
    }
}
