<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

final class BooksResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            "id"         => $this->id,
            "title"      => $this->title,
            "author"     => $this->author,
            "stock"      => $this->stock,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
