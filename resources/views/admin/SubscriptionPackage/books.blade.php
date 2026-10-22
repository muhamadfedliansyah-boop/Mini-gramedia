@extends('layout.app')

@section('content')
  <div class="container mt-3">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.subscription-packages.books.store', $subscriptionPackage->id) }}" id="addBookForm" method="POST">
      @csrf
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Data Buku yang akan dipilih untuk paket langganan <b>{{ $subscriptionPackage->name }}</b></h3>
        <button type="submit" id="btnSumbiitBooks" class="btn btn-primary d-none">
          <i class="fas fa-plus me-1"></i>
          Tambah ke Paket Langganan (<span id="countSelected">0</span>)
        </button>
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
      // Set untuk menyimpan ID buku yang dipilih lintas halaman DataTables
      let selectedIds = new Set();

      let table = $("#data-buku").DataTable({
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
            data: 'id',
            name: 'id',
            searchable: false,
            orderable: false,
            // render(data, type, row): data = nilai kolom id, row = seluruh data baris
            render: function (data,type,row) {
              if (row.CheckAdded === 'Yes') {
                return ``;
              }
              return `<input type="checkbox" name="book_id[]"
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

      // Setelah DataTables render ulang (ganti halaman/sort), restore checkbox yang sudah dipilih
      table.on('draw', function () {
        $('.book-checkbox').each(function () {
          let id = $(this).val();
          if (selectedIds.has(id)) {
            $(this).prop('checked', true);
          }
        });
        updateButtonVisibility();
      });

      function updateButtonVisibility() {
        let count = selectedIds.size;
        $('#countSelected').text(count);
        if (count > 0) {
          $('#btnSumbiitBooks').removeClass('d-none');
        } else {
          $('#btnSumbiitBooks').addClass('d-none');
        }
      }

      // Saat centang/uncentang checkbox, update Set dan tampilan button
      $(document).on('change', '.book-checkbox', function () {
        let id = $(this).val();
        if ($(this).is(':checked')) {
          selectedIds.add(id);
        } else {
          selectedIds.delete(id);
        }
        updateButtonVisibility();
      });

      // Saat submit form, inject semua ID dari Set sebagai hidden input
      $('#addBookForm').on('submit', function () {
        // Hapus hidden input lama agar tidak duplikat
        $(this).find('input[type="hidden"][name="book_id[]"]').remove();
        selectedIds.forEach(function (id) {
          $('<input>').attr({ type: 'hidden', name: 'book_id[]', value: id })
            .appendTo('#addBookForm');
        });
      });
    });
  </script>
@endpush