@extends('layouts.main')

@section('content')
    <div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <div>
                <h4 class="fw-bold mb-1">Accounts Table</h4>
                <p class="text-muted small mb-0">Add, delete, update accounts</p>
            </div>
            <div class="d-flex gap-2 flex-shrink-0">
                <a href="#" class="btn btn-theme px-3">
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
                            <tr>
                                <td class="ps-4">1</td>
                                <td class="fw-bold">Admin Utama</td>
                               <td>admin@example.com</td>
                                <td><span class="badge bg-theme-light text-theme fw-normal">Admin</span></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-theme px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal1">
                                            Edit
                                        </button>
                                        <form action="#" method="POST">
                                            <button type="button" class="btn btn-danger btn-sm px-3">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4">2</td>
                                <td class="fw-bold">Staff Inventori</td>
                               <td>staff@example.com</td>
                                <td><span class="badge bg-theme-light text-theme fw-normal">Staff</span></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-theme px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal2">
                                            Edit
                                        </button>
                                        <form action="#" method="POST">
                                            <button type="button" class="btn btn-danger btn-sm px-3">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="editUserModal1" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Account Forms</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted small">Please fill all input form with right value.</p>
                                            <form action="#" method="POST">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control" value="Admin Utama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control" value="admin@example.com" required>
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
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="" disabled selected hidden>Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                            </select>
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
