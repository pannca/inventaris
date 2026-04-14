<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\ItemExport;

class ItemController extends Controller
{
    public function index()
    {
        // mengambil semua data barang beserta nama kategorinya
        $items = Item::with('category')->get();
        // mengambil semua kategori untuk di tampilkan di form select (dropdown)
        $categories = Category::all();

        return view('admin.items.index', compact('items', 'categories'));
    }

    // menyimpan data barang baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'total' => 'required|integer|min:1',
        ], [
            'name.required' => 'Nama barang wajib diisi',
            'category_id.required' => 'Kategori barang wajib dipilih',
            'total.required' => 'Jumlah barang wajib diisi',
            'total.integer' => 'Jumlah barang harus berupa angka',
            'total.min' => 'Jumlah barang minimal 1',
        ]);

        Item::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'total' => $request->total,
            // saat pertama kali barang di masukkan stok tersedia sama dengan stok total
            'available' => $request->total,
        ]);

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil ditambahkan');
    }

    // Mengupdate data barang (Termasuk update status Repair)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'total' => 'required|integer|min:1',
            'repair' => 'required|integer|min:0' // Tambahan validasi untuk repair
        ]);

        $item = Item::findOrFail($id);

        // Logika Keamanan Jumlah repair tidak boleh melebihi sisa barang yang ada di sekolah
        // (Total barang - Barang yang sedang dipinjam orang)
        $maksimal_repair = $request->total - $item->lending_total;

        if ($request->repair > $maksimal_repair) {
            return back()->withErrors(['repair' => 'Jumlah barang rusak tidak valid! Maksimal barang yang bisa diset rusak saat ini adalah ' . $maksimal_repair]);
        }

        // Sisa stok yang tersedia dihitung ulang
        $stok_tersedia = $request->total - $item->lending_total - $request->repair;

        $item->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'total' => $request->total,
            'repair' => $request->repair, // Update nilai repair di database
            'available' => $stok_tersedia // Update stok available pakai rumus di atas
        ]);

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully!');
    }

    // // Menghapus data barang
    // public function destroy($id)
    // {
    //     $item = Item::findOrFail($id);
    //     $item->delete();

    //     return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully!');
    // }

    // Fitur Export Excel
    public function exportExcel()
    {
        return (new ItemExport)->download();
    }

    // method khusus staff
    public function staffIndex()
    {
        $items = Item::with('category')->latest()->get();
        return view ('staff.items.index', compact('items'));
    }
}
