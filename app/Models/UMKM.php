<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UMKM extends Model
{
    use HasFactory;

    // Nama tabel yang sesuai di database
    protected $table = 'umkm_profiles';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'user_id',
        'nama_usaha',
        'jenis_usaha',
        'deskripsi',
        'alamat',
        'no_telepon',
        'email',
        'tahun_berdiri',
        'jumlah_karyawan',
    ];

    // Relasi: UMKM dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
