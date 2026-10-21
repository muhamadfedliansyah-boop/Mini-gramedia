@extends('layout.app')

@section('content')
  <div class="container mt-3">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form action="{{ route('admin.subscription-packages.books.create', $subscriptionPackage->id) }}" method="POST">
      @csrf
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Data Buku yang akan dipilih untuk paket langganan <b>{{ $subscriptionPackage->name }}</b></h3>
        <button type="submit" class="btn btn-primary">Simpan Pilihan Buku</button>
      </div>
      <table class="table table-bordered mt-3" id="data-buku">
        <thead>
          <tr>
            <th></th>
            <th>#</th>
            <th>Sampul Buku</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Harga</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </form>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function () {
      $("#data-buku").DataTable({
        // menampilkan ikon loading
        processing: true,
        // menggunakan server side (data diproses di controller)
        serverSide: true,
        // routing yang memproses datatables
        ajax: "{{ route('admin.subscription-packages.books', $subscriptionPackage->id) }}",
        // isi td dari tablenya
        columns: [
          // data dan name: nama kolom, searchable: bisa disearch gak datanya, orderable: bisa diurutin gak datanya
          {
            data:'id',
            name:'id',
            searchable:false,
            orderable:false,
            render: function (data) {
              return `<input type="checkbox" name="book_ids[]" 
              value="${data}" class="form-check-input book-checkbox">`;
            }
          },
          {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'coverImg',
            name: 'coverImg',
            orderable: false,
            searchable: false
          },
          {
            data: 'title',
            name: 'title'
          },
          // data : dari nama field/addcolumn/editcolumn
          // name : bookCategory.name : untuk search/sort carinya dari relasi
          {
            data: 'book_category_id',
            name: 'bookCategory.name'
          },
          {
            data: 'price',
            name: 'price'
          },
        ]
      });
    });
  </script>
@endpush