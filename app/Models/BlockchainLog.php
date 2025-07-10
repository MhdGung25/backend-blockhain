<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockchainLog extends Model
{
    use HasFactory;

    protected $table = 'blockchain_logs';

    protected $fillable = [
        'user_id',
        'hash',
        'jenis',
        'jumlah',
        'deskripsi',
    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}