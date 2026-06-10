@extends('layouts.app')
@section('title', 'Tambah Ruangan')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Tambah Ruangan</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.ruangan.store') }}" method="POST" enctype="multipart/form-data">
                    @include('admin.ruangan._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
