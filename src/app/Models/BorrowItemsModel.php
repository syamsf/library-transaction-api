<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowItemsModel extends Model {
    use HasFactory;

    protected $table = 'borrow_items';

    protected $fillable = [
        'borrow_id',
        'book_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function borrow(): BelongsTo {
        return $this->belongsTo(BorrowsModel::class, 'borrow_id');
    }

    public function book(): BelongsTo {
        return $this->belongsTo(BooksModel::class, 'book_id');
    }
}
