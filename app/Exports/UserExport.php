<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Rap2hpoutre\FastExcel\FastExcel;

class UserExport
{
    public function download($filterType = null, $filterValue = null)
    {
        $query = User::query();

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

        $users = $query->get()->map(function ($user) {
            $emailPrefix = substr($user->email, 0, 4);
            $defaultPassword = $emailPrefix . $user->id;

            $passwordText = Hash::check($defaultPassword, $user->password)
                ? $defaultPassword
                : 'This account already edited the password';

            return [
                'Name'     => $user->name,
                'Email'    => $user->email,
                'Password' => $passwordText,
            ];
        });

        return (new FastExcel($users))->download('users.xlsx');
    }
}
