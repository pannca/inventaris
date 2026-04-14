@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="fw-bold mb-1">Categories Table</h4>
            <p class="text-muted small mb-0">Add, delete, update categories</p>
        </div>
        <button type="button" class="btn btn-theme px-3 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus"></i>
            <span class="d-none d-md-inline"> Add</span>
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            
        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      
    </div>
</div>
@endsection
