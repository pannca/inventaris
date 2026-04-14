<?php

namespace App\Exports;

use App\Models\Lending;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;

class LendingExport
{
    public function download($filterType = null, $filterValue = null)
    {
        $query = Lending::with('item')->latest();

        if ($filterType && $filterValue) {
            if ($filterType === 'date') {
                $query->whereDate('date', $filterValue);
            } elseif ($filterType === 'month') {
                [$year, $month] = explode('-', $filterValue);
                $query->whereYear('date', $year)->whereMonth('date', $month);
            } elseif ($filterType === 'year') {
                $query->whereYear('date', $filterValue);
            }
        }

        $lendings = $query->get()->map(function ($lending) {
            $date = Carbon::parse($lending->date)->format('M d, Y');
            $returnDate = $lending->return_date
                ? Carbon::parse($lending->return_date)->format('M d, Y')
                : ' - ';

            return [
                'Item'        => $lending->item->name ?? '-',
                'Total'       => $lending->total,
                'Name'        => $lending->name,
                'Ket.'        => $lending->ket,
                'Date'        => $date,
                'Return Date' => $returnDate,
                'Edited By'   => $lending->edited_by,
            ];
        });

        return (new FastExcel($lendings))->download('lendings.xlsx');
    }
}
