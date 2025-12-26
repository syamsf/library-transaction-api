<?php

namespace App\Http\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateBookRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title'  => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'stock'  => 'required|integer|min:0',
        ];
    }
}
