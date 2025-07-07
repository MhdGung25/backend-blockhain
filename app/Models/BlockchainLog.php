<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockchainLog extends Model
{
    protected $fillable = [
        'user_id',
        'hash',
        'jenis',
        'jumlah',
        'deskripsi',
    ];
}
