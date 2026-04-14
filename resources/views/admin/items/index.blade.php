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
                Terdapat inputan yang tidak sesuai, silakan cek kembali.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <div>
                <h4 class="fw-bold mb-1">Items Table</h4>
                <p class="text-muted small mb-0">Add, update items</p>
            </div>
            <div class="d-flex gap-2 flex-shrink-0">
                <a href="{{ route('admin.items.export') }}" class="btn btn-theme px-3"
                    data-bs-toggle="modal" data-bs-target="#exportFilterModal">
                    <i class="fas fa-file-excel d-md-none"></i>
                    <span class="d-none d-md-inline">Export Excel</span>
                </a>
                <button type="button" class="btn btn-theme px-3" data-bs-toggle="modal" data-bs-target="#addItemModal">
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
                                <th>Category</th>
                                <th>Name</th>
                                <th>Total</th>
                                <th>Repair</th>
                                <th>Lending</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>{{ $item->category->name ?? '-' }}</td>
                                    <td class="fw-bold">{{ $item->name }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>{{ $item->repair }}</td>
                                    <td>
                                        @if ($item->lending_total > 0)
                                            <a href="{{ route('admin.items.lending', $item->id) }}"
                                                class="fw-bold text-primary text-decoration-underline">
                                                {{ $item->lending_total }}
                                            </a>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-sm btn-theme px-3" data-bs-toggle="modal"
                                                data-bs-target="#editItemModal{{ $item->id }}">
                                                Edit
                                            </button>
                                            {{-- <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm px-3">Delete</button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Item Forms</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-muted small">Please fill input form with right value.</p>
                                                <form action="{{ route('admin.items.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" name="name" class="form-control"
                                                            value="{{ $item->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Category</label>
                                                        <select name="category_id" class="form-select" required>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Total</label>
                                                        <input type="number" name="total" class="form-control"
                                                            value="{{ $item->total }}" min="1" required>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label">Repair (Barang Rusak)</label>
                                                        <input type="number" name="repair" class="form-control"
                                                            value="{{ $item->repair }}" min="0" required>
                                                        <small class="text-muted">Isi dengan 0 jika tidak ada barang
                                                            rusak.</small>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-secondary w-50"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-theme w-50">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data barang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Item Forms</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Please fill input form with right value.</p>
                    <form action="{{ route('admin.items.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Contoh: Komputer" required>
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                                required>
                                <option value="" disabled selected hidden>Pilih Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Total</label>
                            <input type="number" name="total"
                                class="form-control @error('total') is-invalid @enderror" value="{{ old('total') }}"
                                placeholder="Contoh: 15" min="1" required>
                            @error('total')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
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
{{-- Export Filter Modal --}}
<div class="modal fade" id="exportFilterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Export Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm" action="{{ route('admin.items.export') }}" method="GET" target="_blank">
                    <div class="mb-3">
                        <label class="form-label">Filter By</label>
                        <select name="filter_type" id="filterType" class="form-select">
                            <option value="">Semua Data</option>
                            <option value="date">Tanggal</option>
                            <option value="month">Bulan</option>
                            <option value="year">Tahun</option>
                        </select>
                    </div>
                    <div class="mb-4" id="filterValueWrapper" style="display:none">
                        <label class="form-label">Nilai Filter</label>
                        <input type="text" name="filter_value" id="filterValue" class="form-control">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-theme w-50">Download</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('filterType').addEventListener('change', function () {
        const wrapper = document.getElementById('filterValueWrapper');
        const input = document.getElementById('filterValue');
        const type = this.value;
        if (!type) { wrapper.style.display = 'none'; return; }
        wrapper.style.display = 'block';
        input.type = type === 'date' ? 'date' : (type === 'month' ? 'month' : 'number');
        if (type === 'year') { input.placeholder = 'Contoh: 2024'; input.min = 2000; input.max = 2100; }
    });
</script>
@endsection
