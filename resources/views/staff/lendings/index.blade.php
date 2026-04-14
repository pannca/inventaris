@extends('layouts.main')

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <div>
                <h4 class="fw-bold mb-1">Lendings Table</h4>
                <p class="text-muted small mb-0">Manage item lendings</p>
            </div>
            <div class="d-flex gap-2 flex-shrink-0">
                <a href="{{ route('staff.lendings.export') }}" class="btn btn-theme px-3">
                    <i class="fas fa-file-excel d-md-none"></i>
                    <span class="d-none d-md-inline">Export Excel</span>
                </a>
                <button type="button" class="btn btn-theme px-3" data-bs-toggle="modal" data-bs-target="#addLendingModal">
                    <i class="fas fa-plus"></i>
                    <span class="d-none d-md-inline"> Add</span>
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">#</th>
                                <th>Name</th>
                                <th>Item</th>
                                <th>Date</th>
                                <th>Return Date</th>
                                <th>Total</th>
                                <th>Ket</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lendings as $index => $lending)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $lending->name }}</td>
                                    <td>{{ $lending->item->name ?? 'Barang Dihapus' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lending->date)->format('d-m-Y') }}</td>
                                    <td class="text-center">
                                        @if ($lending->return_date)
                                            <span class="badge bg-theme-light text-theme fw-normal">{{ \Carbon\Carbon::parse($lending->return_date)->format('d-m-Y') }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark fw-normal">Not Returned</span>
                                        @endif
                                    </td>
                                    <td>{{ $lending->total }}</td>
                                    <td>{{ $lending->ket ?? '-' }}</td>
                                    <td class="text-center">
                                        @if (is_null($lending->return_date))
                                            <form action="{{ route('staff.lendings.return', $lending->id) }}" method="POST"
                                                onsubmit="return confirm('Konfirmasi bahwa barang ini telah dikembalikan?');">
                                                @csrf
                                                <button type="submit" class="btn btn-theme btn-sm px-3">Returned</button>
                                            </form>
                                        @else
                                            <span class="text-theme small"><i class="fas fa-check-circle"></i> Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Belum ada data peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addLendingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Lending Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Please fill all input form with right value.</p>
                    <form action="{{ route('staff.lendings.store') }}" method="POST" id="form-lending">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Nama Siswa/Peminjam">
                        </div>

                        <div id="dynamic-item-container">
                            <div class="item-row row mb-2 align-items-end">
                                <div class="col-8">
                                    <label class="form-label">Items</label>
                                    <select name="items[]" class="form-select" required>
                                        <option value="" disabled selected hidden>Select Items</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Total</label>
                                    <input type="number" name="totals[]" class="form-control" placeholder="total" required min="1">
                                </div>
                                <div class="col-1"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <a href="javascript:void(0)" id="btn-more" class="text-info text-decoration-none small">
                                <i class="fas fa-chevron-down"></i> More
                            </a>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Ket.</label>
                            <textarea name="ket" class="form-control" rows="2" required></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-theme w-50">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnMore = document.getElementById('btn-more');
            const container = document.getElementById('dynamic-item-container');

            btnMore.addEventListener('click', function() {
                const firstRow = container.querySelector('.item-row');
                const newRow = firstRow.cloneNode(true);
                newRow.querySelector('select').value = '';
                newRow.querySelector('input[type="number"]').value = '';
                const actionCol = newRow.querySelector('.col-1');
                actionCol.innerHTML = `
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 mt-2 btn-remove-row" title="Hapus">
                        <i class="fas fa-times fs-5"></i>
                    </button>
                `;
                container.appendChild(newRow);
            });

            container.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.btn-remove-row');
                if (removeBtn) {
                    removeBtn.closest('.item-row').remove();
                }
            });
        });
    </script>
@endsection
