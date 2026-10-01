@extends('layout.app')

@section('content')
    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="card w-75 d-block mx-auto">
        @csrf
        <div class="card-header">
            <h3>Tambah Buku</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-6">
                    <label for="book_category_id" class="form-label">Kategori Buku</label>
                    <select name="book_category_id" id="book_category_id" class="form-select">
                        <option disabled hidden selected>Pilih Kategori Buku</option>
                        @foreach ($bookCategories as $category)
                            <option value="{{ $category->id }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                    @error('book_category_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="title" class="form-label">Judul</label>
                    <input type="text" name="title" id="title" class="form-control">
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="writer" class="form-label">Penulis</label>
                    <input type="text" name="writer" id="writer" class="form-control">
                    @error('writer')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="publisher" class="form-label">Penerbit</label>
                    <input type="text" name="publisher" id="publisher" class="form-control">
                    @error('publisher')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" id="price" class="form-control">
                    @error('price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="language" class="form-label">Bahasa</label>
                    <select name="language" id="language" class="form-select">
                        <option value="" disabled hidden selected>Pilih Bahasa</option>
                        <option value="Indo">Indo</option>
                        <option value="English">English</option>
                        <option value="Japan">Japan</option>
                        <option value="Arabic">Arabic</option>
                    </select>
                    @error('language')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="release_date" class="form-label">Tanggal terbit</label>
                    <input type="date" name="release_date" id="release_date" class="form-control">
                    @error('release_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="page_of_book" class="form-label">Jumlah Halaman</label>
                    <input type="number" name="page_of_book" id="page_of_book" min="1" class="form-control">
                    @error('page_of_book')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 mb-3">
                    <label for="cover" class="form-label">Sampul Buku</label>
                    <input type="file" name="cover" id="cover" class="form-control">
                    @error('cover')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <div id="description">
                        <div id="editor"></div>
                    </div>
                    <input type="hidden" name="description" id="description_input">
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row" style="margin-top: 8%">
                <div class="col12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const quill = new Quill('#description', {
            theme: 'snow'
        });

        document.querySelector('#description_input').value = "-";
        quill.on('text-change', function () {
            document.querySelector('#description_input').value = quill.root.innerHTML;
        });
    </script>
@endpush
