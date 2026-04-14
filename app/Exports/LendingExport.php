<?php

namespace App\Exports;

use App\Models\Lending;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;

class LendingExport
{
    public function download()
    {
        // Ambil data lending beserta relasi item-nya
        $lendings = Lending::with('item')->latest()->get()->map(function ($lending) {

            // Format tanggal peminjaman 
            $date = Carbon::parse($lending->date)->format('M d, Y');

            // Logic Return Date sesuai PDF, kalau null kasih  - 
            if ($lending->return_date) {
                $returnDate = Carbon::parse($lending->return_date)->format('M d, Y');
            } else {
                $returnDate = ' - ';
            }

            return [
                'Item'        => $lending->item->name ?? '-', // Ambil nama barang dari relasi
                'Total'       => $lending->total,
                'Name'        => $lending->name,
                'Ket.'        => $lending->ket,
                'Date'        => $date,
                'Return Date' => $returnDate,
                'Edited By'   => $lending->edited_by,
            ];
        });

        // Download dengan nama file sesuai PDF
        return (new FastExcel($lendings))->download('lendings.xlsx');
    }
}
