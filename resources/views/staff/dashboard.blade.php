@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Dashboard</h4>
        <p class="text-muted small mb-0">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>

    <div class="row g-3">
        <div class="col-6 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background-color:#e8edff;">
                        <i class="fas fa-box fa-lg" style="color:#2b43cc;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Jenis Barang</p>
                        <h5 class="fw-bold mb-0">{{ \App\Models\Item::count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background-color:#e8f5e9;">
                        <i class="fas fa-check-circle fa-lg" style="color:#2e7d32;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Stok Tersedia</p>
                        <h5 class="fw-bold mb-0">{{ \App\Models\Item::sum('available') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background-color:#fff3e0;">
                        <i class="fas fa-hand-holding fa-lg" style="color:#e65100;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Sedang Dipinjam</p>
                        <h5 class="fw-bold mb-0">{{ \App\Models\Lending::whereNull('return_date')->sum('total') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
