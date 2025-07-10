<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jumlah',
        'alasan',
        'bank',
        'logo_url',
        'tenor',
        'status',
    ];

    // Relasi: Setiap pengajuan dimiliki oleh 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
