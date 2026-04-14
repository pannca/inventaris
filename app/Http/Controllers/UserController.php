<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index(Request $request)
    {
        // Ambil semua data user, filter by role jika ada query parameter
        $query = User::query();
        
        if ($request->has('role') && in_array($request->role, ['admin', 'staff'])) {
            $query->where('role', $request->role);
        }
        
        $users = $query->latest()->get();
        $selectedRole = $request->input('role');
        
        return view('admin.users.index', compact('users', 'selectedRole'));
    }

    // Menyimpan akun baru (Auto-Generate Password sesuai PDF)
    public function store(Request $request)
    {
        // Validasi tanpa 'password' karena akan di-generate otomatis oleh sistem
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,staff',
        ]);

        // Ambil 4 karakter pertama dari email inputan
        $emailPrefix = substr($request->email, 0, 4);

        // Buat user dengan password sementara (karena kita butuh ID-nya dulu)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make('temp'), // Password sementara
        ]);

        // Generate password asli sesuai aturan PDF: 4 huruf awal email + ID
        $rawPassword = $emailPrefix . $user->id;

        // Update user dengan password asli yang sudah di-hash
        $user->update([
            'password' => Hash::make($rawPassword)
        ]);

        // Kembalikan raw password ke alert sesuai instruksi PDF
        return redirect()->route('admin.users.index', ['role' => $request->role])
            ->with('success', 'Akun berhasil dibuat! Password: ' . $rawPassword);
    }

    // Mengupdate akun
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            // new_password opsional, tidak wajib diisi
        ]);

        $user = User::findOrFail($id);

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Kalau form 'new password' diisi, maka update passwordnya
        if ($request->filled('new_password')) {
            $dataToUpdate['password'] = Hash::make($request->new_password);
        }

        $user->update($dataToUpdate);

        return redirect()->route('admin.users.index', ['role' => $user->role])->with('success', 'Akun berhasil diupdate!');
    }

    // Menghapus akun
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menghapus akunnya sendiri yang sedang dipakai login
        if (Auth::user()->id === $user->id) {
            return redirect()->route('admin.users.index', ['role' => 'admin'])->withErrors(['error' => 'Anda tidak bisa menghapus akun Anda sendiri!']);
        }

        $userRole = $user->role;
        $user->delete();

        return redirect()->route('admin.users.index', ['role' => $userRole])->with('success', 'Akun berhasil dihapus!');
    }

    // Export Excel Users
    public function exportExcel()
    {
        // Panggil logic export dari folder App\Exports
        $export = new UserExport();
        return $export->download();
    }

    // Method Khusus Staff: Nampilin form edit profile diri sendiri
    public function editProfile()
    {
        $user = Auth::user(); // Ambil data user yang lagi login
        return view('staff.users.edit', compact('user'));
    }

    // Method Khusus Staff: Proses update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Ambil data user yang lagi login

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            // new_password opsional
        ]);

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('new_password')) {
            $dataToUpdate['password'] = Hash::make($request->new_password);
        }

        User::where('id', $user->id)->update($dataToUpdate);

        return redirect()->back()->with('success', 'Profile berhasil diupdate!');
    }

    // Method Khusus Staff: Menampilkan daftar user
    public function staffIndex()
    {
        // Ambil semua user
        $users = User::latest()->get();
        
        return view('staff.users.index', compact('users'));
    }

    // Method Khusus Staff: Mengupdate akun user
    public function staffUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            // new_password opsional, tidak wajib diisi
        ]);

        $user = User::findOrFail($id);

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Kalau form 'new password' diisi, maka update passwordnya
        if ($request->filled('new_password')) {
            $dataToUpdate['password'] = Hash::make($request->new_password);
        }

        $user->update($dataToUpdate);

        return redirect()->route('staff.users.index')->with('success', 'Akun berhasil diupdate!');
    }
}
