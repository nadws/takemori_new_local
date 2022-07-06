<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'tb_voucher';
    protected $fillable = [
        'kode', 'jumlah','updated_at', 'ket', 'expired', 'status', 'lokasi', 'terpakai', 'admin', 'id_voucher'
    ];
}
