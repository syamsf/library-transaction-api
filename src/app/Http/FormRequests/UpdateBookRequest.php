<?php

namespace App\Http\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateBookRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title'  => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'stock'  => 'nullable|integer|min:0',
        ];
    }
}
