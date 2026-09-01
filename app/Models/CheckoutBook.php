<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


#[Fillable(['user_id', 'payment_method', 'total_book', 'total_book_price', 'total_price', 'status'])]

class CheckoutBook extends Model
{
   public function checkout(): BelongsTo {
        return $this->belongsTo(Checkout::class);
    }

    public function book(): BelongsTo {
        return $this->belongsTo(Book::class);
    }
}
