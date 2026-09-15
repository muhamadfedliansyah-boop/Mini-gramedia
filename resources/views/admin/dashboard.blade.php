@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <p>Welcome, {{ Auth::check() ? Auth::user()->name : 'Admin' }}!</p>
    </div>
@endsection