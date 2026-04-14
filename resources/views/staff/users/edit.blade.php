@extends('layouts.main')

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12 col-md-8 col-lg-6 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Edit Account Forms</h5>
                    <small class="text-muted">Please fill all input form with right value.</small>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('staff.users.update') }}" method="POST">
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
                            <input type="password" name="new_password" class="form-control" placeholder="Input new password here...">
                        </div>

                        <div class="d-flex gap-2 flex-column flex-sm-row">
                            {{-- <button type="reset" class="btn btn-secondary flex-sm-grow-1">Cancel</button> --}}
                            <button type="submit" class="btn btn-theme flex-sm-grow-1">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
