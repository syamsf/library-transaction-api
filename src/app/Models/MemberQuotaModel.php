<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberQuotaModel extends Model {
    protected $table = 'member_quota';

    protected $fillable = [
        'member_id',
        'quota',
    ];
}
