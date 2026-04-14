@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Detail Lending</h4>
        <p class="text-muted small mb-0">
            Item: <span class="fw-semibold">{{ $item->name }}</span>
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">#</th>
                            <th>Nama Peminjam</th>
                            <th>Total</th>
                            <th>Tanggal Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lendings as $index => $lending)
                            <tr>
                                <td class="ps-4">{{ $index + 1 }}</td>
                                <td>{{ $lending->name }}</td>
                                <td>{{ $lending->total }}</td>
                                <td>{{ $lending->date }}</td>
                                <td>
                                    @if ($lending->return_date)
                                        <span class="badge bg-success">{{ \Carbon\Carbon::parse($lending->return_date)->format('d-m-Y') }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Not Returned</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Tidak ada data peminjaman untuk item ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">
            ← Kembali ke Items
        </a>
    </div>

</div>
@endsection