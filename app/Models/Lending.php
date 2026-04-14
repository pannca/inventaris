<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lending extends Model
{
    protected $fillable = [
        'item_id',
        'name',
        'total',
        'ket',
        'date',
        'return_date',
        'edited_by'
    ];

    // Relasi balik ke Item (Transaksi ini minjam barang apa?)
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
