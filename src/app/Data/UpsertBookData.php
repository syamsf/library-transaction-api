<?php

namespace App\Data;

use Illuminate\Http\Request;

final class UpsertBookData {
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $author = null,
        public readonly ?int    $stock = null
    ) {
    }

    public static function fromRequest(Request $request): self {
        return new self(
            title: $request->input('title'),
            author: $request->input('author'),
            stock: $request->input('stock'),
        );
    }

    public function toCreate(): array {
        return [
            "title" => $this->title,
            "author" => $this->author,
            "stock" => $this->stock,
        ];
    }

    public function toUpdate(): array {
        $data = [];

        if (!empty($this->title)) {
            $data["title"] = $this->title;
        }

        if (!empty($this->author)) {
            $data["author"] = $this->author;
        }

        if (!is_null($this->stock)) {
            $data["stock"] = $this->stock;
        }

        return $data;
    }
}
