<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

final class BorrowResource extends BaseResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'member' => [
                'id' => $this->member?->id,
                'name' => $this->member?->name,
                'email' => $this->member?->email,
            ],
            'borrowed_at' => $this->borrowed_at?->toISOString(),
            'items' => $this->items?->map(function ($item) {
                return [
                    'id' => $item->id,
                    'book_id' => $item->book_id,
                    'book' => [
                        'id' => $item->book?->id,
                        'title' => $item->book?->title,
                        'author' => $item->book?->author,
                    ],
                    'quantity' => $item->quantity,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
