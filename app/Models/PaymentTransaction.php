<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'payment_order_id',
        'transaction_reference',
        'amount',
        'payment_method',
        'status',
        'response_code',
        'response_message',
        'processed_at'
    ];

    public function paymentOrder()
    {
        return $this->belongsTo(PaymentOrder::class);
    }
}
