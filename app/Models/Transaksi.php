<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis'; // pastikan nama tabel sesuai

    protected $fillable = [
        'user_id',
        'jenis',       // bisa bernilai 'pemasukan' atau 'pengeluaran'
        'deskripsi',
        'jumlah',
        'hash',        // hash transaksi dari blockchain
    ];

    /**
     * Relasi ke model User (satu transaksi dimiliki satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
