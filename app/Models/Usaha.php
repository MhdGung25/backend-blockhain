<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usaha extends Model
{
    protected $fillable = [
        'nama_usaha',
        'jenis_usaha',
        'tahun_berdiri',
        'alamat',
    ];
}
