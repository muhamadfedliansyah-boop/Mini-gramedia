@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <h3>Pembayaran</h3>
        <div class="row mt-3">
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="text-center">Scan QR Code</h5>
                    <p class="text-center text-secondary">Gunakan aplikasi e-wallet atau m-banking
                        anda untuk memindai QR Code</p>
                </div>
                <div class="d-flex justify-content-center">
                    {!! QrCode::size(250)->generate($subscriptionPackage->id); !!}
                </div>
            </div>
        </div>
        <div class="col-4">
            
        </div>
    </div>
@endsection