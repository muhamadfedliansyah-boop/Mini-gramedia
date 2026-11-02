@extends('layout.app')

@section('content')
<div class="container py-4">

    {{-- Banner Paket (Warna Paket + Nama & Deskripsi) --}}
    <div class="card border-0 rounded-3 shadow-sm mb-4 overflow-hidden"
         style="background: linear-gradient(135deg, #ffffff 0%, {{ $subscriptionPackage->color ?? '#f3e8ff' }}33 50%, {{ $subscriptionPackage->color ?? '#f3e8ff' }}66 100%); min-height: 180px;">
        <div class="card-body p-4 p-md-5 d-flex align-items-center justify-content-center text-center">
            <div>
                <h1 class="display-5 fw-bold text-uppercase mb-2" style="letter-spacing: -0.5px; color: {{ $subscriptionPackage->color ?? '#b0439b' }};">
                    {{ strtoupper($subscriptionPackage->name) }} PACKAGE
                </h1>
                <p class="text-secondary fs-5 mb-0" style="max-width: 650px; margin: 0 auto;">
                    {{ $subscriptionPackage->description ?? 'Cocok untuk penikmat cerita yang menggugah emosi dan menghidupkan imajinasi' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Detail Paket & Harga --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">{{ ucwords($subscriptionPackage->name) }} Package</h2>
        </div>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <span class="fs-2 fw-bold text-dark">
                    Rp{{ number_format($subscriptionPackage->price, 0, ',', '.') }}
                </span>
                <span class="badge bg-danger-subtle text-danger px-2 py-1">20%</span>
                <span class="text-muted text-decoration-line-through small">
                    Rp{{ number_format($subscriptionPackage->price * 1.25, 0, ',', '.') }}
                </span>
            </div>
            <a href="{{ route('subscription-packages.checkout.page', $subscriptionPackage->id) }}" class="btn btn-primary rounded-pill px-4 fw-semibold">
                Langganan Sekarang
            </a>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="d-flex justify-content-center justify-content-md-end mb-4">
        <form action="{{ url()->current() }}" method="GET" class="w-100" style="max-width: 420px;">
            <div class="input-icon">
                <span class="input-icon-addon">
                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                </span>
                <input type="text"
                       name="search"
                       id="searchPackageBook"
                       value="{{ request('search') }}"
                       class="form-control rounded-pill pe-4"
                       placeholder="Cari dalam paket ini"
                       autocomplete="off">
            </div>
        </form>
    </div>

    {{-- Daftar Buku dalam Paket (Grid 6 per baris persis seperti gambar) --}}
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3" id="booksGrid">
        @forelse ($subscriptionPackage->books as $book)
            <div class="col book-item-col"
                 data-title="{{ strtolower($book->title) }}"
                 data-writer="{{ strtolower($book->writer ?? '') }}">
                <div class="card h-100 border rounded-3 p-2 d-flex flex-column bg-white shadow-none">
                    {{-- Cover Buku --}}
                    <div class="rounded-2 overflow-hidden bg-light d-flex align-items-center justify-content-center mb-2" style="height: 180px;">
                        @if ($book->cover)
                            <img src="{{ asset($book->cover) }}" alt="{{ $book->title }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        @else
                            <i class="fa-solid fa-book fa-3x text-muted"></i>
                        @endif
                    </div>

                    {{-- Informasi Buku --}}
                    <div class="d-flex flex-column flex-fill">
                        <div class="d-flex align-items-center gap-1 mb-1">
                            <span class="badge bg-light text-secondary border px-1 py-0" style="font-size: 0.65rem;">
                                <i class="fa-solid fa-file-pdf text-danger me-1"></i>PDF
                            </span>
                            <span class="badge bg-light text-secondary border px-1 py-0" style="font-size: 0.65rem;">
                                17+
                            </span>
                        </div>
                        <small class="text-muted text-truncate d-block" style="font-size: 0.72rem;" title="{{ $book->writer }}">
                            {{ $book->writer ?? 'Penulis Gramedia' }}
                        </small>
                        <span class="fw-bold text-dark text-truncate d-block" style="font-size: 0.82rem;" title="{{ $book->title }}">
                            {{ $book->title }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center w-100">
                <p class="text-secondary fs-4 mb-2">Tidak ada buku dalam paket langganan ini.</p>
                @if (request('search'))
                    <a href="{{ url()->current() }}" class="btn btn-outline-primary btn-sm rounded-pill">
                        Lihat Semua Buku
                    </a>
                @endif
            </div>
        @endforelse

        {{-- Pesan Kosong Live Search --}}
        <div class="col-12 py-5 text-center w-100 d-none" id="emptyLiveSearch">
            <p class="text-secondary fs-4 mb-0">Buku tidak ditemukan dalam paket ini.</p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#searchPackageBook').on('input', function () {
            var q = $(this).val().toLowerCase().trim();
            var found = 0;

            $('.book-item-col').each(function () {
                var title = $(this).data('title') || '';
                var writer = $(this).data('writer') || '';

                if (title.indexOf(q) !== -1 || writer.indexOf(q) !== -1) {
                    $(this).removeClass('d-none');
                    found++;
                } else {
                    $(this).addClass('d-none');
                }
            });

            if (found === 0 && q !== '') {
                $('#emptyLiveSearch').removeClass('d-none');
            } else {
                $('#emptyLiveSearch').addClass('d-none');
            }
        });
    });
</script>
@endpush
