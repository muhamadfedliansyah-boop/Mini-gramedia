@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Tambah Paket Langganan</h2>
            <a href="{{ route('admin.paket-langganan.create') }}" class="btn btn-primary mb-3">
                Tambah paket langganan
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-body">


           <table class="table table-boardered table-resposive bg-white">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Paket</th>
                        <th>Deskripsi</th>
                        <th>Warna</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ( $subscriptionPackages as $subscriptionPackage )
                        <tr>
                            <td>{{ $loop->iteration}}</td>
                            <td>{{ $subscriptionPackage->name }}</td>
                            <td>{{ filled(trim($subscriptionPackage->description ?? '')) ? $subscriptionPackage->description : 'PAKET LENGKAP' }}</td>
                            <td>
                                <span style="display:inline-block; width:20px; height:20px; background-color:{{ $subscriptionPackage->color }}; border-radius:4px;"></span>
                                {{ $subscriptionPackage->color }}
                            </td>
                            <td>Rp {{ number_format($subscriptionPackage->price, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.paket-langganan.edit', $subscriptionPackage->id) }}"
                                    class="btn btn-warning">Edit</a>
                                <form action="{{ route('admin.paket-langganan.destroy', $subscriptionPackage->id) }}"
                                     method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

           </table>
        </div>
    </div>
@endsection
