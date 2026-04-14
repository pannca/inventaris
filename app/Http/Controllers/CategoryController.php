<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {

        $categories = Category::withCount('items')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'division_pj' => 'required',
        ], [
            'name.required' => 'Kolom nama wajib di isi.',
            'division_pj.required' => 'Kolom divisi pj wajib diisi.',
        ]);

        Category::create([
            'name' => $request->name,
            'division_pj' => $request->division_pj,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, $id) {

        $request->validate([
            'name' => 'required',
            'division_pj' => 'required',
        ], [
            'name.required' => 'Kolom nama wajib di isi.',
            'division_pj.required' => 'Kolom divisi pj wajib diisi.',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'division_pj' => $request->division_pj,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    // // proses hapus category
    // public function destroy($id) {
    //     $category = Category::findOrFail($id);
    //     $category->delete();

    //     return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    // }
}
