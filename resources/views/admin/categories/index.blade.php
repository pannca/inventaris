@extends('layouts.main')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="fw-bold mb-1">Categories Table</h4>
            <p class="text-muted small mb-0">Add,update categories</p>
        </div>
        <button type="button" class="btn btn-theme px-3 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus"></i>
            <span class="d-none d-md-inline"> Add</span>
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">#</th>
                            <th>Name</th>
                            <th>Division PJ</th>
                            <th>Total Items</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                        <tr>
                            <td class="ps-4">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $category->name }}</td>
                            <td>{{ $category->division_pj }}</td>
                            <td>{{ $category->items_count }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-theme px-3"
                                        data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                        Edit
                                    </button>
                                    {{-- <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini? Semua barang di dalamnya juga akan terhapus!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm px-3">Delete</button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Category Forms</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted small">Please fill input form with right value.</p>
                                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label">Division PJ</label>
                                                <select name="division_pj" class="form-select" required>
                                                    <option value="Sarpras" {{ $category->division_pj == 'Sarpras' ? 'selected' : '' }}>Sarpras</option>
                                                    <option value="Tata Usaha" {{ $category->division_pj == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                                                    <option value="Tefa" {{ $category->division_pj == 'Tefa' ? 'selected' : '' }}>Tefa</option>
                                                </select>
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
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Category Forms</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Please input form with right value.</p>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Contoh: Alat Dapur">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Division PJ</label>
                        <select name="division_pj" class="form-select @error('division_pj') is-invalid @enderror">
                            <option value="" disabled selected hidden>Select Division PJ</option>
                            <option value="Sarpras" {{ old('division_pj') == 'Sarpras' ? 'selected' : '' }}>Sarpras</option>
                            <option value="Tata Usaha" {{ old('division_pj') == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                            <option value="Tefa" {{ old('division_pj') == 'Tefa' ? 'selected' : '' }}>Tefa</option>
                        </select>
                        @error('division_pj') <span class="text-danger small">{{ $message }}</span> @enderror
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
@endsection
