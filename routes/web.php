<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Halaman awal (landing page) untuk login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// route khusus admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // halaman dashboard admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Rute CRUD Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Rute CRUD Items
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    // export item
    Route::get('/items/export', [ItemController::class, 'exportExcel'])->name('items.export');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Rute CRUD Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/export', [UserController::class, 'exportExcel'])->name('users.export');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Route khusus staff

Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {

    // Halaman Dasbor Staff
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');

    // --- TAMBAHAN UNTUK ITEMS STAFF ---
    Route::get('/items', [ItemController::class, 'staffIndex'])->name('items.index');

    Route::get('/users', [UserController::class, 'editProfile'])->name('users.index');
    Route::put('/users/update', [UserController::class, 'updateProfile'])->name('users.update');

    // Rute CRUD Lending
    Route::get('/lendings', [LendingController::class, 'index'])->name('lendings.index');
    // export lending
    Route::get('/lendings/export', [LendingController::class, 'exportExcel'])->name('lendings.export');
    Route::post('/lendings', [LendingController::class, 'store'])->name('lendings.store');
    // Rute khusus untuk tombol "Returned"
    Route::post('/lendings/return/{id}', [LendingController::class, 'returnItem'])->name('lendings.return');
});
