<?php

namespace App\Repositories;

use App\Models\PaymentOrder;
use App\Models\PaymentTransaction;

class PaymentTransactionRepository
{
    public function getPaymentOrderByMerchant(int $merchantId,int $paymentOrderId): ?PaymentOrder
    {
        return PaymentOrder::where('id', $paymentOrderId)->where('merchant_id', $merchantId)->first();
    }

    public function createTransaction(array $data): PaymentTransaction
    {
        return PaymentTransaction::create($data);
    }

    public function updatePaymentOrder(PaymentOrder $paymentOrder,array $data): bool
    {
        return $paymentOrder->update($data);
    }

    public function getTransactionHistory(PaymentOrder $paymentOrder)
    {
        return $paymentOrder->transactions()->latest('processed_at')->get();
    }
}
