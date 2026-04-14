<?php

namespace App\Exports;

use App\Models\Item;
use Rap2hpoutre\FastExcel\FastExcel;

class ItemExport
{
    public function download()
    {
        $urutan = 1;
        $items = Item::with('category')->get()->map(function ($item) use (&$urutan) {
            return [
                'No'                       => $urutan++,
                'Kategori Barang'          => $item->category->name ?? '-',
                'Nama Barang'              => $item->name,
                'Total Stok'               => $item->total,
                'Sedang Diperbaiki (Repair)' => $item->repair,
                'Sedang Dipinjam (Lending)'  => $item->lending_total,
                'Stok Tersedia (Available)'  => $item->available,
            ];
        });

        return (new FastExcel($items))->download('items.xlsx');
    }
}
