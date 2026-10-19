@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kategori Buku</h2>
        <a href="{{ route('admin.book-categories.create') }}" class="btn btn-primary">Tambah Kategori Buku</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
    <table class="table table-bordered table-responsive bg-white" id="book-categories-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $("#book-categories-table").DataTable({
            //menampilkan icon loadinh
            processing: true,
            //menggunakan server side (data diproses di controller)
            serverSide: true,
            //routing yang memproses database
            ajax: "{{ route('admin.book-categories.index') }}",
            //isi td dari tabel nya
            columns: [
                //data dan name: nama kolom, searchable : bisa di search ga datanya, orderable: bisa di urutin ga datanya
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name', orderable: true, searchabel: true},
                {data: 'action', name: 'action', orderable: false, searchabel: false}
            ]
        })
    })
</script>
@endpush
