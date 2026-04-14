@extends('layouts.main')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <div>
                <h4 class="fw-bold mb-1">Lendings Table</h4>
                <p class="text-muted small mb-0">Manage item lendings</p>
            </div>
            <div class="d-flex gap-2 flex-shrink-0">
                <a href="#" class="btn btn-theme px-3">
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
                   
                </div>
            </div>
        </div>
    </div>

    

    <script>
     
    </script>
@endsection
