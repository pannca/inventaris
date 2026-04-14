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
                Terdapat kesalahan:
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
                <h4 class="fw-bold mb-1">Accounts Table</h4>
                <p class="text-muted small mb-0">Add, delete, update accounts</p>
            </div>
            <div class="d-flex gap-2 flex-shrink-0">
                <a href="{{ route('admin.users.export') }}" class="btn btn-theme px-3"
                    data-bs-toggle="modal" data-bs-target="#exportFilterModal">
                    <i class="fas fa-file-excel d-md-none"></i>
                    <span class="d-none d-md-inline">Export Excel</span>
                </a>
                <button type="button" class="btn btn-theme px-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
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
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $user)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-theme-light text-theme fw-normal">{{ ucfirst($user->role) }}</span></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-sm btn-theme px-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editUserModal{{ $user->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm px-3">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Account Forms</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-muted small">Please fill all input form with right value.</p>
                                                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label">New Password</label>
                                                        <small class="text-warning mb-2 d-block">Kosongkan jika tidak ingin mengubah password.</small>
                                                        <input type="password" name="new_password" class="form-control" placeholder="Input new password here...">
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-theme w-50">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data akun.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Account Forms</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Please fill all input form with right value.</p>
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="" disabled selected hidden>Select Role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                            @error('role') <span class="text-danger small">{{ $message }}</span> @enderror
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
                <form action="{{ route('admin.users.export') }}" method="GET" target="_blank">
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
