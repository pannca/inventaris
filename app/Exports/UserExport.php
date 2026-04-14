<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Rap2hpoutre\FastExcel\FastExcel;

class UserExport
{
    public function download()
    {
        // Ambil semua data user, lalu manipulasi datanya pakai map()
        $users = User::all()->map(function ($user) {

            // Reconstruct password default sesuai aturan 4 huruf awal email + ID
            $emailPrefix = substr($user->email, 0, 4);
            $defaultPassword = $emailPrefix . $user->id;

            // Cek apakah password di database masih sama dengan password default
            if (Hash::check($defaultPassword, $user->password)) {
                $passwordText = $defaultPassword;
            } else {
                // Sesuai PDF, jika sudah diganti tampilkan pesan ini
                $passwordText = 'This account already edited the password';
            }

            // Return susunan kolom sesuai yang ada di PDF Excel
            return [
                'Name'     => $user->name,
                'Email'    => $user->email,
                'Password' => $passwordText,
            ];
        });

        // Download file Excel-nya
        return (new FastExcel($users))->download('users.xlsx');
    }
}
