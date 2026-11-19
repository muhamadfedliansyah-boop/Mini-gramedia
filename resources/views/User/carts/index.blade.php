@extends('layout.app')

@section('content')
    <div class="container my-4">
        <h2 class="fw-bod mb-4">Keranjang Belanja</h2>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    tutup
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    tutup
                </button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border rounded-3 p-3 mb-3 shadow-sm">
                    <div class="form-check d-flex align-items-center">
                        <label class="form-check-label fw-medium" for="check=all">
                            <span id="cart-count">{{$carts->count()}}</span>
                            Buku terpilih
                        </label>
                    </div>
                </div>

                    @forelse ($carts as $cart)
                        <div class="card border rounded-3 p-3 mb-3 shadow-sm position-relative">
                            <div class="d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input item-checkbox" type="checkbox" name="selected_carts[]"
                                        value="{{$cart->id}}" data-price="{{ $cart->display_price }}" checked>
                                </div>

                                <div class="flex-srink-0 me-3">
                                    <img src="{{ $cart->book->cover ?? '' }}" alt="{{ $cart->book->title ??'Tanpa Buku' }}" class="rounded"
                                        style="width: 70px; height: 100px; object-fit: cover;">
                                </div>

                                <div class="flex-grow-1">
                                    <div class="text-muted small">
                                        {{$cart->book->writer ?? 'Penulis tidak di ketahui'}}
                                    </div>
                                    <h4 class="fw-bold mb-0 text-dark">
                                        {{$cart->book->title ?? 'Judul tidak di ketahui'}}
                                    </h4>
                                </div>

                                @php
                                    $price = $cart->subscriptionPackage ? $cart->subscriptionPackage->price : $cart->book->price;
                                @endphp

                                <div class="text-end d-flex flex-column aligin-itmes-end justify-content-between
                                style-price-container" style="height: 100px">

                                <div>
                                    <h4 class="fw-bold mb-0">
                                        Rp {{ number_format($cart->display_price, 0, ',', '.') }}
                                    </h4>
                                    <div class="small">
                                        @if ($cart->subscriptionPackage)
                                           <span class="badge bg-green text-green-fg">{{ $cart->subscriptionPackage->name }}</span>
                                        @else
                                            <span class="badge bg-azure text-azure-fg">
                                                Medium
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                    <form action="{{ route('carts.destroy', $cart->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100 py-2.5
                                        rounded-pill fw-semibold">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card p-5 text-center text-muted border ">
                            <p>Belum ada item di keranjang</p>
                        </div>
                    @endforelse
                </div>
                  <div class="col-lg-4">
                    <div class="card border rounded-4 p-4 shadow-sm position-sticky" style="top: 20px">
                        <h5 class="fw-bold mb-4">
                            Ringkasan Pembelian
                        </h5>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span> Total Belanja
                                (<span id="total-selected-count">{{ $carts->count() }}</span> buku)
                            </span>
                            <span class="text-dark fw-medium" id="total-price-text">Rp 0</span>
                        </div>

                        <div class="mb-2">
                            @foreach ($carts as $cart)
                                <div class="d-flex justify-content-between small mb-1 summary-row {{ $cart->needs_package ? 'text-danger' : 'text-muted' }}" data-cart-id="{{ $cart->id }}">
                                    <span class="text-truncate pe-3 {{ $cart->needs_package ? 'text-danger' : '' }}">
                                        {{ $cart->book->title }}
                                    </span>

                                    <span class="fw-medium {{ $cart->needs_package ? 'text-danger' : '' }}" style="text-align: right;">
                                        Rp {{ number_format($cart->display_price, 0, ',', '.') }}
                                        @if ($cart->needs_package)
                                            <br><small class="text-danger">
                                                Perlu membeli paket {{ $cart->subscriptionPackage->name }}
                                            </small>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-3">
                        <div class="d-flex justify-content-between mc-3 text-muted">
                            <span> Total bayar</span>
                            <span class="text-dark" id="total-payment-text">
                                Rp {{ number_format($totalPrice, 0, ',', '.') }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('checkout.store') }}" id="form-checkout">
                            @csrf
                            <div id="checkout-inputs"></div>
                                <button type="submit" id="btn-checkout" class="btn btn-primary w-100 py-2.5
                                rounded-pill fw-semibold mt-3">
                                    Checkout
                                </button>
                        </form>

                    </div>
                </div>

            </div>


        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalPriceText = document.getElementById('total-price-text');
        const totalPaymentText = document.getElementById('total-payment-text');
        const totalCountText = document.getElementById('total-selected-count');
        const cartCount = document.getElementById('cart-count');
        const btnCheckout = document.getElementById('btn-checkout');
        const checkoutInputs = document.getElementById('checkout-inputs');

        // Fungsi untuk format rupiah
        const rupiah = n => 'Rp ' + n.toLocaleString('id-ID');

        function updateSummary() {
            // ambil element checkboxes, filter (ambil) hanya yang checked (terceklis)
            const checked = [...checkboxes].filter(cb => cb.checked);
            // reduce : looping kemudian buat nilai baru yang akan disimpan ke variable pembungkus reduce (const total). mempunyai 2 parameter, sum (variable penyimpan data yg sedang diolah), cb (checkbox yang sedang dilooping)
            // dataset : mengambil value dari atribut element HTML yang diawali dengan "data-" (data-price)
            const total = checked.reduce((sum, cb) => sum + Number(cb.dataset.price), 0);

            // textContent : mengubah isi text dari element HTML
            totalPriceText.textContent = rupiah(total);
            if (totalPaymentText) {
                totalPaymentText.textContent = rupiah(total);
            }
            totalCountText.textContent = checked.length;
            cartCount.textContent = `(${checked.length})`;

            checkboxes.forEach(cb => {
                // ambil class summary-row yang spesifik berdasarkan data cart id nya
                const row = document.querySelector(`.summary-row[data-cart-id="${cb.value}"]`);
                if (row) {
                    if (cb.checked) { // jika checkbox yg sedang di loop diceklis, hilangkan d-none
                        row.classList.remove('d-none');
                    } else { // jika tidak, masukkan d-none (class untuk menyembunyikan element HTML)
                        row.classList.add('d-none');
                    }
                }
            });

            if (checked.length === 0) {
                btnCheckout.disabled = true; // Matikan tombol jika tidak ada yang dicentang
            } else {
                btnCheckout.disabled = false; // Aktifkan tombol jika ada yang dicentang
            }

            // isi nilai default dari HTML yang akan disimpan di const checkoutInputs
            checkoutInputs.innerHTML = '';
            // looping checkbox yg di ceklis (const checked) untuk membuat input hidden yang akan dikirim ke controller saat btn checkout di klik. name dan value sama seperti checkbox
            checked.forEach(cb => {
                checkoutInputs.innerHTML += `<input type="hidden" name="selected_carts[]" value="${cb.value}">`;
            });
        }

        // event listener untuk setiap checkbox, jika ada yang berubah (diceklis atau tidak), jalankan fungsi updateSummary
        checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));

        // jalankan sekali di awal agar total dan status awal langsung terhitung
        updateSummary();
    });
</script>
@endpush
