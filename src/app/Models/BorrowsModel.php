<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowsModel extends Model {
    use HasFactory;

    protected $table = 'borrows';

    protected $fillable = [
        'member_id',
        'borrowed_at',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
    ];

    public function member(): BelongsTo {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function items(): HasMany {
        return $this->hasMany(BorrowItemsModel::class, 'borrow_id');
    }
}
