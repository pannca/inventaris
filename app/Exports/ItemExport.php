<?php

namespace App\Exports;

use App\Models\Item;
use Rap2hpoutre\FastExcel\FastExcel;

class ItemExport
{
    public function download($filterType = null, $filterValue = null)
    {
        $query = Item::with('category');

        if ($filterType && $filterValue) {
            if ($filterType === 'date') {
                $query->whereDate('created_at', $filterValue);
            } elseif ($filterType === 'month') {
                [$year, $month] = explode('-', $filterValue);
                $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            } elseif ($filterType === 'year') {
                $query->whereYear('created_at', $filterValue);
            }
        }

        $urutan = 1;
        $items = $query->get()->map(function ($item) use (&$urutan) {
            return [
                'No'                         => $urutan++,
                'Kategori Barang'            => $item->category->name ?? '-',
                'Nama Barang'                => $item->name,
                'Total Stok'                 => $item->total,
                'Sedang Diperbaiki (Repair)' => $item->repair,
                'Sedang Dipinjam (Lending)'  => $item->lending_total,
                'Stok Tersedia (Available)'  => $item->available,
            ];
        });

        return (new FastExcel($items))->download('items.xlsx');
    }
}
