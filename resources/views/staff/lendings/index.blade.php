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
                <a href="{{ route('staff.lendings.export') }}" class="btn btn-theme px-3"
                    data-bs-toggle="modal" data-bs-target="#exportFilterModal">
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
                                <th>TTD</th>
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
                                        @elseif ($lending->returned_total > 0)
                                            <span class="badge bg-info text-white fw-normal">Partial ({{ $lending->returned_total }}/{{ $lending->total }})</span>
                                        @else
                                            <span class="badge bg-warning text-dark fw-normal">Not Returned</span>
                                        @endif
                                    </td>
                                    <td>{{ $lending->total - $lending->returned_total }}</td>
                                    <td>{{ $lending->ket ?? '-' }}</td>
                                    <td>
                                        @if ($lending->signature)
                                            <img src="{{ asset('storage/' . $lending->signature) }}"
                                                style="height:40px;cursor:pointer;border:1px solid #dee2e6;border-radius:4px;"
                                                data-bs-toggle="modal" data-bs-target="#sigModal{{ $lending->id }}"
                                                title="Klik untuk lihat TTD">
                                            <div class="modal fade" id="sigModal{{ $lending->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title fw-bold">TTD &mdash; {{ $lending->name }}</h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ asset('storage/' . $lending->signature) }}" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (is_null($lending->return_date))
                                            <button type="button" class="btn btn-theme btn-sm px-3"
                                                data-bs-toggle="modal" data-bs-target="#returnModal{{ $lending->id }}">
                                                Returned
                                            </button>

                                            <div class="modal fade" id="returnModal{{ $lending->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">Form Pengembalian</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="text-muted small">Peminjam: <strong>{{ $lending->name }}</strong> &mdash; Item: <strong>{{ $lending->item->name ?? '-' }}</strong></p>
                                                            <p class="text-muted small">Sisa belum dikembalikan: <strong>{{ $lending->total - $lending->returned_total }}</strong> dari total <strong>{{ $lending->total }}</strong></p>
                                                            <form action="{{ route('staff.lendings.return', $lending->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="return_signature" class="return-signature-input">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Jumlah Dikembalikan</label>
                                                                    <input type="number" name="returned_total" class="form-control"
                                                                        min="1" max="{{ $lending->total - $lending->returned_total }}"
                                                                        placeholder="Maks: {{ $lending->total - $lending->returned_total }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Keterangan Kondisi</label>
                                                                    <textarea name="return_ket" class="form-control" rows="2"
                                                                        placeholder="Contoh: Barang kembali dalam kondisi baik / rusak" required></textarea>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tanda Tangan <span class="text-danger">*</span></label>
                                                                    <div class="border rounded p-1" style="background:#f8f9fa">
                                                                        <canvas class="return-signature-canvas" width="430" height="120"
                                                                            style="width:100%;cursor:crosshair;touch-action:none;"></canvas>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mt-1">
                                                                        <small class="text-muted">Tanda tangan di kotak atas</small>
                                                                        <a href="javascript:void(0)" class="btn-clear-return-sig text-danger small">Hapus TTD</a>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-2">
                                                                    <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-theme w-50">Konfirmasi</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-theme small"><i class="fas fa-check-circle"></i> Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">Belum ada data peminjaman.</td>
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

                        <input type="hidden" name="signature" id="signatureInput">

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Nama Siswa/Peminjam" value="{{ old('name') }}">
                        </div>

                        <div id="dynamic-item-container">
                            <div class="item-row row mb-2 align-items-end">
                                <div class="col-8">
                                    <label class="form-label">Items</label>
                                    <select name="items[]" class="form-select" required>
                                        <option value="" disabled selected hidden>Select Items</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} (Stok: {{ $item->available }})</option>
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
                            <textarea name="ket" class="form-control" rows="2" required>{{ old('ket') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanda Tangan <span class="text-danger">*</span></label>
                            <div class="border rounded p-1" style="background:#f8f9fa">
                                <canvas id="signatureCanvas" width="430" height="150" style="width:100%;cursor:crosshair;touch-action:none;"></canvas>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Tanda tangan di kotak di atas</small>
                                <a href="javascript:void(0)" id="btnClearSignature" class="text-danger small">Hapus TTD</a>
                            </div>
                            @error('signature')
                                <div class="text-danger small mt-1">{{ $message }}</div>
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
                <form action="{{ route('staff.lendings.export') }}" method="GET" target="_blank">
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
    document.addEventListener('DOMContentLoaded', function () {
        @if($errors->any())
        new bootstrap.Modal(document.getElementById('addLendingModal')).show();
        @endif

        // Export filter
        document.getElementById('filterType').addEventListener('change', function () {
            const wrapper = document.getElementById('filterValueWrapper');
            const input = document.getElementById('filterValue');
            const type = this.value;
            if (!type) { wrapper.style.display = 'none'; return; }
            wrapper.style.display = 'block';
            input.type = type === 'date' ? 'date' : (type === 'month' ? 'month' : 'number');
            if (type === 'year') { input.placeholder = 'Contoh: 2024'; input.min = 2000; input.max = 2100; }
        });

        // Dynamic item rows
        const btnMore = document.getElementById('btn-more');
        const container = document.getElementById('dynamic-item-container');

        btnMore.addEventListener('click', function () {
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

        container.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-row');
            if (removeBtn) {
                removeBtn.closest('.item-row').remove();
            }
        });

        // Return modal signature canvas (per modal)
        document.querySelectorAll('[id^="returnModal"]').forEach(function (modal) {
            modal.addEventListener('shown.bs.modal', function () {
                const canvas = modal.querySelector('.return-signature-canvas');
                if (!canvas || canvas._initialized) return;
                canvas._initialized = true;
                const ctx = canvas.getContext('2d');
                let drawing = false;

                function getPos(e) {
                    const rect = canvas.getBoundingClientRect();
                    const scaleX = canvas.width / rect.width;
                    const scaleY = canvas.height / rect.height;
                    if (e.touches) return { x: (e.touches[0].clientX - rect.left) * scaleX, y: (e.touches[0].clientY - rect.top) * scaleY };
                    return { x: (e.clientX - rect.left) * scaleX, y: (e.clientY - rect.top) * scaleY };
                }

                canvas.addEventListener('mousedown', (e) => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
                canvas.addEventListener('mousemove', (e) => { if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
                canvas.addEventListener('mouseup', () => { drawing = false; });
                canvas.addEventListener('mouseleave', () => { drawing = false; });
                canvas.addEventListener('touchstart', (e) => { e.preventDefault(); drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
                canvas.addEventListener('touchmove', (e) => { e.preventDefault(); if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
                canvas.addEventListener('touchend', () => { drawing = false; });

                modal.querySelector('.btn-clear-return-sig').addEventListener('click', () => ctx.clearRect(0, 0, canvas.width, canvas.height));

                const form = modal.querySelector('form');
                form.addEventListener('submit', function (e) {
                    const blank = document.createElement('canvas');
                    blank.width = canvas.width; blank.height = canvas.height;
                    if (canvas.toDataURL() === blank.toDataURL()) {
                        e.preventDefault();
                        alert('Tanda tangan pengembalian wajib diisi!');
                        return;
                    }
                    modal.querySelector('.return-signature-input').value = canvas.toDataURL('image/png');
                });
            });
        });

        // Signature canvas (add lending form)
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');
        let drawing = false;

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            if (e.touches) {
                return {
                    x: (e.touches[0].clientX - rect.left) * scaleX,
                    y: (e.touches[0].clientY - rect.top) * scaleY
                };
            }
            return {
                x: (e.clientX - rect.left) * scaleX,
                y: (e.clientY - rect.top) * scaleY
            };
        }

        canvas.addEventListener('mousedown', (e) => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
        canvas.addEventListener('mousemove', (e) => { if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
        canvas.addEventListener('mouseup', () => { drawing = false; });
        canvas.addEventListener('mouseleave', () => { drawing = false; });
        canvas.addEventListener('touchstart', (e) => { e.preventDefault(); drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
        canvas.addEventListener('touchmove', (e) => { e.preventDefault(); if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
        canvas.addEventListener('touchend', () => { drawing = false; });

        document.getElementById('btnClearSignature').addEventListener('click', function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        document.getElementById('form-lending').addEventListener('submit', function (e) {
            const blank = document.createElement('canvas');
            blank.width = canvas.width;
            blank.height = canvas.height;
            if (canvas.toDataURL() === blank.toDataURL()) {
                e.preventDefault();
                alert('Tanda tangan wajib diisi sebelum meminjam!');
                return;
            }
            document.getElementById('signatureInput').value = canvas.toDataURL('image/png');
        });
    });
</script>
@endsection
