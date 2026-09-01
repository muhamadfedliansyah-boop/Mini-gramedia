<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['subscription_package_id', 'book_id', 'expired_date'])]

class SubscriptionPackageBook extends Model
{
    public function subscriptionPackage(): BelongsTo {
        return $this->belongsTo(SubscriptionPackage::class);
    }

    public function  book(): belongsTo {
        return $this->belongsTo(Book::class);
    }

}
