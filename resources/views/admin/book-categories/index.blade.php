@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Tambah Kategori Buku</h2>
            <a href="{{ route('admin.book-categories.create') }}" class="btn btn-primary mb-3">
                Tambah Kategori buku
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection
