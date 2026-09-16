@extends('layout.app')
@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-item-center mb-3">
        <h2>Tambah Kategori Buku</h2>
        <a href="{{ route('admin.book-categories.create') }} class="btn btn-primary mb-3">Tambah Kategori Buku</a>
    </div>
</div>
@endsection
