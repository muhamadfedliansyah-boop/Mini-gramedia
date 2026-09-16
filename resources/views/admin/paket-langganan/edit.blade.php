@extends('layout.app')

@section('content')
    <div class="card mt-5 w-50 mx-auto ">
        <div class="card-header">
            <h1>Edit Kategori Paket Langganan</h1>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.paket-langganan.update', $subscriptionPackage->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Paket</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $subscriptionPackage->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $subscriptionPackage->price) }}" min="0" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="3">{{ old('description', $subscriptionPackage->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="color" class="form-label">Warna</label>
                    <input type="color" class="form-control @error('color') is-invalid @enderror"
                           id="color" name="color" value="{{ old('color', $subscriptionPackage->color ?? '#007bff') }}">
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection
