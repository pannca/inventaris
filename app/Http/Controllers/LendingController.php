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
        // Validasi array dari form yang punya tombol "More"
        $request->validate([
            'name' => 'required',
            'items' => 'required|array|min:1',     // items sekarang array
            'items.*' => 'required',
            'totals' => 'required|array|min:1',    // totals sekarang array
            'totals.*' => 'required|integer|min:1',
            'ket' => 'required', // Di PDF Ket itu wajib diisi
        ]);

        // Gunakan Database Transaction biar kalau 1 barang gagal validasi, semua proses dibatalkan (aman)
        DB::beginTransaction();

        try {
            // Looping array barang yang diinput
            foreach ($request->items as $index => $itemId) {
                // Kunci row item ini sementara biar gak ada bentrok data
                $item = Item::lockForUpdate()->findOrFail($itemId);

                $qtyToLend = $request->totals[$index];

                // Cek validasi persis seperti teks di PDF
                if ($qtyToLend > $item->available) {
                    DB::rollBack();

                    return back()
                        ->withErrors(['error' => 'Total item more than available!'])
                        ->withInput();
                }

                // Catat ke tabel lendings
                Lending::create([
                    'item_id' => $itemId,
                    'name' => $request->name,
                    'total' => $qtyToLend,
                    'ket' => $request->ket,
                    'date' => Carbon::now()->toDateString(), // Otomatis tanggal hari ini
                    'return_date' => null,
                    'edited_by' => Auth::user()->name,
                ]);

                // Update stok di tabel items
                $item->update([
                    'available' => $item->available - $qtyToLend,
                    'lending_total' => $item->lending_total + $qtyToLend,
                ]);
            }

            // Kalau semua loop aman, simpan permanen ke database
            DB::commit();

            // Pesan sukses persis sesuai PDF
            return redirect()->route('staff.lendings.index')->with('success', 'Success add new lending item!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Terjadi kesalahan: '.$e->getMessage()]);
        }
    }

    // Proses pengembalian barang
    public function returnItem($id)
    {
        $lending = Lending::findOrFail($id);

        // Kalau barang sudah dikembalikan sebelumnya, batalkan
        if ($lending->return_date != null) {
            return back()->withErrors(['error' => 'Barang ini sudah dikembalikan!']);
        }

        // Update return_date jadi hari ini
        $lending->update([
            'return_date' => Carbon::now()->toDateString(),
        ]);

        // Kembalikan stok ke tabel items
        $item = Item::findOrFail($lending->item_id);
        $item->update([
            'available' => $item->available + $lending->total,
            'lending_total' => $item->lending_total - $lending->total,
        ]);

        return redirect()->route('staff.lendings.index')->with('success', 'Barang berhasil dikembalikan!');
    }

    // Export data peminjaman ke Excel
    public function exportExcel()
    {
        $export = new LendingExport;

        return $export->download();
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
