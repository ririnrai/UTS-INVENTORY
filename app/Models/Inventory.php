<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'nama_barang',
        'no_barang',
        'jumlah_barang',
        'jenis_barang',
        'tanggal_masuk_keluar',
        'role',
        'session',
        'timestamp',
        'date_session',
    ];

    protected $casts = [
        'tanggal_masuk_keluar' => 'date',
        'date_session' => 'datetime',
        'timestamp' => 'time',
    ];
}
