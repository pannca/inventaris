@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Dashboard</h4>
        <p class="text-muted small mb-0">Selamat datang, Admin Utama!</p>
    </div>

    <div class="row g-3">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background-color:#e8edff;">
                        <i class="fas fa-box fa-lg" style="color:#2b43cc;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Total Barang</p>
                        <h5 class="fw-bold mb-0">120</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background-color:#f0f3ff;">
                        <i class="fas fa-list fa-lg" style="color:#5c73e7;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Kategori</p>
                        <h5 class="fw-bold mb-0">12</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background-color:#e0f2fe;">
                        <i class="fas fa-hand-holding fa-lg" style="color:#0ea5e9;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Dipinjam</p>
                        <h5 class="fw-bold mb-0">15</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background-color:#f1f5f9;">
                        <i class="fas fa-tools fa-lg" style="color:#64748b;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Diperbaiki</p>
                        <h5 class="fw-bold mb-0">5</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
