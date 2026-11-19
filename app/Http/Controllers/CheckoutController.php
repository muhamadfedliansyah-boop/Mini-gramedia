<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Checkout;
use App\Models\CheckoutBook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\Midtrans\CreateSnapTokenService;


class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'selected_carts' => ['required', 'array', 'min:1'],
            'selected_carts.*' => ['required', 'integer', 'exists:carts,id'],
        ]);

        $cartItems = Cart::whereIn('id', $validated['selected_carts'])
            ->with('book')
            ->where('user_id', Auth::id())
            ->get();
            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Data keranjang Kosong');
            }

            $prices = $cartItems->mapWithKeys(fn ($row) => [
                $row->id => $row->subscription_package_id == NULL ? $row->book->price :
                $row->subscriptionPackage->price
            ]);

            $totalprice = $prices->sum();

            DB::beginTransaction();
            try {
                $checkout = Checkout::create([
                    'user_id' => Auth::id(),
                    'total_price' => $totalprice,
                    'status' => 'pending',
                    'order_code' => 'ORD-' . now()->format('ymd') . '-' . strtolower(Str::random(4)),
                    'total_book_price' => $totalprice,
                    'total_book' => $cartItems->count(),
                ]);

                foreach ($cartItems as $cart) {
                   CheckoutBook::create([
                        'checkout_id' => $checkout->id,
                        'book_id' => $cart->book_id,
                        'subscription_checkout_id' => $cart->subscription_package_id,
                        'total_book' => 1,
                        'price' => $prices[$cart->id],
                    ]);
                }
                $cartIds = $cartItems->pluck('id');
                Cart::whereIn('id', $cartIds)->delete();

                DB::commit();
            } catch(\Throwable $th) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Terjadi kesalahan silahkan coba lagi!!');
            }

            return redirect()->route('checkout.show', $checkout->id);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $checkout = Checkout::where('id', $id)
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        $snapToken = $checkout->snap_token;

        if (is_null($snapToken)) {
            $midtrans = new CreateSnapTokenService($checkout);
            $snapToken = $midtrans->getSnapToken();

            $checkout->snap_token = $snapToken;
            $checkout->save();
        }

        return view('User.Checkouts.show', compact('checkout', 'snapToken'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
