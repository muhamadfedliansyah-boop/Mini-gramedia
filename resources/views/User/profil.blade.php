@extends('layout.app')

@section('content')
<div class="container py-4">
    @if (Session::get('success'))
        <div class="alert alert-important alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 12l5 5l10 -10"></path>
                    </svg>
                </div>
                <div>{{ Session::get('success') }}</div>
            </div>
            <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    @endif

    @if (Session::get('error'))
        <div class="alert alert-important alert-danger alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div>{{ Session::get('error') }}</div>
            </div>
            <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    @endif

    <div class="page-header d-print-none mb-4">
        <div class="row align-items-center">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profil Saya</li>
                    </ol>
                </nav>
                <h1 class="page-title text-dark fw-bold fs-2">
                    Profil Pengguna
                </h1>
                <p class="text-secondary mb-0">Informasi akun dan riwayat pembelian paket langganan Anda</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- KIRI: Informasi Profil User --}}
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-4 d-flex flex-column align-items-center justify-content-center text-center rounded-top position-relative" style="background: blue;">
                    <div class="avatar avatar-xl rounded-circle bg-white text-primary shadow mb-2 fs-1 fw-bold" style="width: 72px; height: 72px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h3 class="text-white mb-0 fw-bold fs-3 text-center">{{ $user->name }}</h3>
                </div>
                <div class="card-body">
                    <h4 class="text-secondary fw-bold small mb-3">Detail Akun</h4>
                    
                    <div class="mb-3 d-flex align-items-center">
                        <span class="avatar avatar-sm bg-blue-lt me-3 rounded">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <div>
                            <div class="text-secondary small">Nama Lengkap</div>
                            <div class="fw-semibold text-dark">{{ $user->name }}</div>
                        </div>
                    </div>

                    <div class="mb-3 d-flex align-items-center">
                        <span class="avatar avatar-sm bg-azure-lt me-3 rounded">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <div>
                            <div class="text-secondary small">Alamat Email</div>
                            <div class="fw-semibold text-dark">{{ $user->email }}</div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                        <div>
                            <div class="text-secondary small">Total Langganan</div>
                            <div class="fs-3 fw-bold text-dark">{{ $subscriptions->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TENGAH / KANAN: Riwayat Transaksi Paket Langganan --}}
        <div class="col-lg-8 col-md-7">
            {{-- Header: Transaksi & Filter --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold text-dark mb-0 fs-2">Transaksi</h3>
            </div>

            @if ($subscriptions->isNotEmpty())
                <div class="d-flex flex-column gap-3">
                    @foreach ($subscriptions as $item)
                        <div class="card shadow-sm border" style="border-radius: 8px; border-color: #e5e7eb !important;">
                            {{-- Bagian Atas: Tanggal Transaksi & Status Sukses --}}
                            <div class="d-flex justify-content-between align-items-center px-4 py-2 border-bottom" style="border-color: #f1f3f5 !important;">
                                <span class="text-secondary small" style="font-size: 0.85rem;">
                                    {{ $item->created_at ? $item->created_at->locale('id')->isoFormat('DD MMM Y, HH:mm') : '-' }}
                                </span>
                                <span class="badge" style="background-color: #d1f2d9; color: #1e7e34; font-weight: 500; font-size: 0.8rem; padding: 4px 10px; border-radius: 6px;">
                                    Transaksi Sukses
                                </span>
                            </div>

                            {{-- Bagian Bawah: Nama Paket, Berlaku s/d, Total Pembayaran --}}
                            <div class="px-4 py-3">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                    {{-- Sisi Kiri: Nama Paket --}}
                                    <div style="flex: 1 1 0; min-width: 0;">
                                        <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                                            @if ($item->subscriptionPackage)
                                                <a href="{{ route('subscription-packages.subscription_package_detail', $item->subscriptionPackage->id) }}" class="text-dark text-decoration-none hover-primary">
                                                    {{ $item->subscriptionPackage->name }}
                                                </a>
                                            @else
                                                Paket Tidak Ditemukan
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Sisi Tengah: Masa Berlaku (Badge Merah) --}}
                                    <div class="text-start text-md-center">
                                        <span class="badge text-white px-3 py-2" style="background-color: #b02a37; border-radius: 6px; font-weight: 500; font-size: 0.82rem; letter-spacing: 0.2px; white-space: nowrap;">
                                            Berlaku s/d: {{ $item->expired_date ? \Carbon\Carbon::parse($item->expired_date)->locale('id')->isoFormat('DD MMM Y') : '-' }}
                                        </span>
                                    </div>

                                    {{-- Sisi Kanan: Total Pembayaran --}}
                                    <div class="text-start text-md-end" style="flex: 1 1 0; min-width: 0;">
                                        <div class="text-secondary small" style="font-size: 0.78rem;">Total Pembayaran</div>
                                        <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                                            Rp {{ number_format($item->subscriptionPackage->price ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card shadow-sm border-0 text-center py-5 px-4" style="border-radius: 8px;">
                    <div class="avatar avatar-xl rounded-circle bg-yellow-lt mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="fa-solid fa-crown fs-1 text-warning"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Belum Ada Transaksi</h3>
                    <p class="text-secondary mb-4 mx-auto" style="max-width: 420px;">
                        Anda belum memiliki riwayat transaksi paket langganan. Dapatkan akses ke berbagai buku premium dengan berlangganan paket kami!
                    </p>
                    <div>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fa-solid fa-basket-shopping me-2"></i> Jelajahi Paket Langganan
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-primary:hover {
        color: #206bc4 !important;
    }
</style>
@endpush