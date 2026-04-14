<?php

namespace App\Http\Controllers;

use App\Exports\LendingExport;
use App\Models\Item;
use App\Models\Lending;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LendingController extends Controller
{
    // Nampilin halaman daftar peminjaman
    public function index()
    {
        // Ambil data peminjaman beserta data barangnya
        $lendings = Lending::with('item')->latest()->get();
        // Ambil data barang yang stoknya masih ada (> 0) buat dropdown
        $items = Item::where('available', '>', 0)->get();

        return view('staff.lendings.index', compact('lendings', 'items'));
    }

    // Proses nyatet peminjaman baru (Support Multi-Item / Array)
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'items'      => 'required|array|min:1',
            'items.*'    => 'required',
            'totals'     => 'required|array|min:1',
            'totals.*'   => 'required|integer|min:1',
            'ket'        => 'required',
            'signature'  => 'required',
        ], [
            'signature.required' => 'Tanda tangan wajib diisi sebelum meminjam.',
        ]);

        // Simpan gambar TTD dari base64
        $signatureData = $request->signature;
        $image = str_replace('data:image/png;base64,', '', $signatureData);
        $image = str_replace(' ', '+', $image);
        $fileName = 'signatures/' . uniqid() . '.png';
        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, base64_decode($image));

        DB::beginTransaction();

        try {
            foreach ($request->items as $index => $itemId) {
                $item = Item::lockForUpdate()->findOrFail($itemId);
                $qtyToLend = $request->totals[$index];

                if ($item->available <= 0) {
                    DB::rollBack();
                    return back()
                        ->withErrors(['error' => "Stok barang '{$item->name}' sudah habis, tidak bisa dipinjam."])
                        ->withInput();
                }

                if ($qtyToLend > $item->available) {
                    DB::rollBack();
                    return back()
                        ->withErrors(['error' => "Jumlah peminjaman '{$item->name}' melebihi stok tersedia. Stok tersedia: {$item->available}."])
                        ->withInput();
                }

                Lending::create([
                    'item_id'    => $itemId,
                    'name'       => $request->name,
                    'total'      => $qtyToLend,
                    'ket'        => $request->ket,
                    'date'       => Carbon::now()->toDateString(),
                    'return_date'=> null,
                    'edited_by'  => Auth::user()->name,
                    'signature'  => $fileName,
                ]);

                $item->update([
                    'available'     => $item->available - $qtyToLend,
                    'lending_total' => $item->lending_total + $qtyToLend,
                ]);
            }

            DB::commit();
            return redirect()->route('staff.lendings.index')->with('success', 'Success add new lending item!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // Proses pengembalian barang
    public function returnItem(Request $request, $id)
    {
        $lending = Lending::findOrFail($id);

        if ($lending->return_date != null) {
            return back()->withErrors(['error' => 'Barang ini sudah dikembalikan!']);
        }

        $request->validate([
            'returned_total'   => 'required|integer|min:1|max:' . ($lending->total - $lending->returned_total),
            'return_ket'       => 'required',
            'return_signature' => 'required',
        ], [
            'returned_total.max'        => 'Jumlah melebihi sisa pinjaman. Maksimal yang bisa dikembalikan: ' . ($lending->total - $lending->returned_total) . '.',
            'return_ket.required'       => 'Keterangan pengembalian wajib diisi.',
            'return_signature.required' => 'Tanda tangan pengembalian wajib diisi.',
        ]);

        // Simpan TTD return
        $image = str_replace('data:image/png;base64,', '', $request->return_signature);
        $image = str_replace(' ', '+', $image);
        $fileName = 'signatures/' . uniqid() . '.png';
        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, base64_decode($image));

        $sisaPinjam = $lending->total - $lending->returned_total - $request->returned_total;
        $newReturnedTotal = $lending->returned_total + $request->returned_total;

        $lending->update([
            'returned_total'   => $newReturnedTotal,
            'return_ket'       => $request->return_ket,
            'return_signature' => $fileName,
            'return_date'      => $sisaPinjam <= 0 ? Carbon::now()->toDateString() : null,
        ]);

        $item = Item::findOrFail($lending->item_id);
        $item->update([
            'available'     => $item->available + $request->returned_total,
            'lending_total' => $item->lending_total - $request->returned_total,
        ]);

        return redirect()->route('staff.lendings.index')->with('success', 'Barang berhasil dikembalikan!');
    }

    // Export data peminjaman ke Excel
    public function exportExcel(Request $request)
    {
        return (new LendingExport)->download($request->filter_type, $request->filter_value);
    }

    // method
    public function byItem(Item $item)
    {
        $lendings = Lending::where('item_id', $item->id)
            ->whereNull('return_date')
            ->latest()
            ->get();

        return view('admin.items.lendings', compact('item', 'lendings'));
    }
}
