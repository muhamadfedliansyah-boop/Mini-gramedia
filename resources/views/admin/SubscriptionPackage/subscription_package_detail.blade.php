@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detail Paket Langganan</h1>
        <p>Nama: {{ $subscriptionPackage->name }}</p>
        <p>Harga: {{ $subscriptionPackage->price }}</p>
        <p>Deskripsi: {{ $subscriptionPackage->description }}</p>
    </div>
@endsection