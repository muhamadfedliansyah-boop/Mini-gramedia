@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <h3>Pembayaran</h3>
        <div class="row mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center">Scan QR Code</h5>
                        <p class="text-center text-secondary">Gunakan aplikasi e-wallet atau m-banking
                            anda untuk memindai QR Code</p>
                    </div>
                    <div class="d-flex justify-content-center p-4">
                        {!! QrCode::size(250)->generate($subscriptionPackage->id) !!}
                    </div>
                </div>
            </div>
            <div class="col-4 px-1">
                <div class="card">
                    <form action="{{ route('user.subscription-package.checkout', $subscriptionPackage->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <h4>Detail Pembelian</h4>
                            <div class="d-flex justify-content-between ps-2">
                                <p>Total Item</p>
                                <p class="text-secondary">1</p>
                            </div>
                            <h4>Sub Total</h4>
                            <div class="d-flex justify-content-between ps-2">
                                <p>Total Harga</p>
                                <p class="text-secondary">Rp 
                                    {{ number_format($subscriptionPackage->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="d-flex justify-content-between ps-2 text-danger">
                                <p>Total Diskon</p>
                                <p class="text-secondary">Rp 
                                    {{ number_format(($subscriptionPackage->price * 0.2), 0, ',', '.') }}</p>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h4>Total Bayar</h4>
                                <h4 class="text-primary">Rp 
                                    {{ number_format($subscriptionPackage->price - ($subscriptionPackage->price * 0.2), 0, ',', '.') }}</h4>
                            </div>
                            <button class="btn btn-primary rounded-pill mt-2 w-100" type="submit">Bayar Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection