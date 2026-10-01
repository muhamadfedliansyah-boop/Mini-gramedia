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


           <table class="table table-boardered table-resposive bg-white" id="subscription-packages-table">
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

                </tbody>

           </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#subscription-packages-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.paket-langganan.index')}}",
                columns: [
                    {data: 'DT_RowIndex', orderable:false , searchable: true},
                    {data: 'name', name: 'name', orderable: true, searchable: true},
                    {data: 'description', name: 'description', orderable: true, searchable: true},
                    {data: 'color', name: 'color', orderable: true, searchable: true},
                    {data: 'price', name: 'price', orderable: true, searchable: true},
                    {data: 'action', orderable: true, searchable: false}
                ]
            })
        });
    </script>
@endpush
