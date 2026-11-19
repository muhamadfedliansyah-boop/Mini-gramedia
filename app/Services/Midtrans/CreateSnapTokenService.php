<?php

namespace App\Services\Midtrans;

use App\Models\Checkout;
use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $order;

    public function __construct(protected Checkout $checkout)
    {
        parent::__construct();
    }

    public function getSnapToken()
    {
        $this->checkout->loadMissing(['checkoutBooks', 'checkoutBooks.book', 'user']);

        $params = [
            'transaction_details' => [
                'order_id' => $this->checkout->order_code,
                'gross_amount' => (int)$this->checkout->total_price,
            ],
            'item_details' => $this->checkout->checkoutBooks->map(fn($row) => [
                    'id' => (string)$row->book->id,
                    'price' => (int)$row->price,
                    'quantity' => (int)($row->total_book ?? 1),
                    'name' => \Illuminate\Support\Str::limit($row->book->title, 50),
                ]
            )->all(),

            'customer_details' => [
                'first_name' => $this->checkout->user->name ?? 'Customer',
                'email' => $this->checkout->user->email ?? 'customer@example.com',
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}
