<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BooksModel extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'author',
        'stock',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    public function borrowItems(): HasMany {
        return $this->hasMany(BorrowItemsModel::class, 'book_id');
    }
}
