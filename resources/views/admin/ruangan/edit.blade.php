@extends('layouts.app')
@section('title', 'Edit Ruangan')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Edit Ruangan</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.ruangan.update', $ruangan) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @include('admin.ruangan._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
