<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    protected $table = 'tb_pembelian';
    protected $fillable = [
        'no_nota', 'id_karyawan', 'id_produk', 'nm_karyawan', 'tanggal',
        'tgl_input', 'jumlah', 'harga', 'diskon', 'jml_komisi',
        'total', 'catatan', 'admin', 'no_meja', 'lokasi', 'void', 'pengantar', 'selesai'
    ];
}
