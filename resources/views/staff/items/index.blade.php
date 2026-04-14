@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Items Table</h4>
        <p class="text-muted small mb-0">Data items</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">#</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Total</th>
                            <th>Available</th>
                            <th>Lending Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td class="ps-4">{{ $index + 1 }}</td>
                                <td>{{ $item->category->name ?? '-' }}</td>
                                <td class="fw-bold">{{ $item->name }}</td>
                                <td>{{ $item->total }}</td>
                                <td class="fw-bold text-success">
                                    {{ $item->total - $item->repair - $item->lending_total }}
                                </td>
                                <td>{{ $item->lending_total }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
