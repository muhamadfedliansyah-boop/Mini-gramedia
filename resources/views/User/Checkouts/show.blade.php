@extends('layout.app')

@section('content')
    <div class="container my-5">
        <div class="card mt-5">
            <div class="card-body">
                <h3 class="text-center">{{ $checkout->order_code }}</h3>
                <p class="text-secondary">{{ \Carbon\Carbon::parse($checkout->created_at)->format('d F Y') }}</p>

                <hr>

                @foreach ($checkout->checkoutBooks as $item)
                    <div class="d-flex justify-content-between mt-2">
                        <p>{{ $item->book->title }}</p>
                        <p>Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between">
                    <h3>Total</h3>
                    <h3>Rp {{ number_format($checkout->total_price, 0, ',', '.') }}</h3>
                </div>

                @if ($checkout->status == 'pending')
                    <button class="btn btn-primary" id="pay-button">Pay Now</button>
                @else
                    <div class="alert alert-info">Pembayaran sudah dilakukan.</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ config('midtrans.snap_js_url') }}" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        const payButton = document.querySelector('#pay-button');
        payButton.addEventListener('click', function(e) { 
            e.preventDefault();

            snap.pay('{{ $snapToken }}', {
                // Optional
                onSuccess: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                },
                // Optional
                onPending: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                },
                // Optional
                onError: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                }
            });
        });
    </script>
@endpush
