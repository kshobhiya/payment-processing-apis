<?php

namespace App\Managers;

use App\Models\PaymentOrder;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Repositories\PaymentTransactionRepository;

class PaymentTransactionManager
{
    protected PaymentTransactionRepository $repository;

    public function __construct(PaymentTransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function processPayment($merchant,int $paymentOrderId,array $request): array {
        return DB::transaction(function () use ($merchant,$paymentOrderId,$request) {
            $paymentOrder = $this->repository->getPaymentOrderByMerchant($merchant->id,$paymentOrderId);

            if (!$paymentOrder) {
                return [
                    'success' => false,
                    'message' => 'Payment Order not found.',
                    'status_code' => 404
                ];
            }

            if ($paymentOrder->status == 'Paid') {
                return [
                    'success' => false,
                    'message' => 'Payment already completed.',
                    'status_code' => 400
                ];
            }

            $approved = rand(0,1);
            $transactionStatus = $approved ? config('payment.transaction_status.approved') : config('payment.transaction_status.rejected');
            $responseCode = $approved ? '00': '05';
            $responseMessage = $approved ? 'Payment Approved' : 'Payment Declined';

            $transaction = $this->repository->createTransaction([
                'payment_order_id' => $paymentOrder->id,
                'transaction_reference' => 'INDIA_TNX-' . strtoupper(substr(Str::orderedUuid()->toString(), 0, 12)),
                'amount' => $paymentOrder->amount,
                'payment_method' => $request['payment_method'],
                'status' => $transactionStatus,
                'response_code' => $responseCode,
                'response_message' => $responseMessage,
                'processed_at' => now()
            ]);

            $this->repository->updatePaymentOrder($paymentOrder,['status' => $approved? config('payment.order_status.paid'): config('payment.order_status.failed')]);
            return [
                'success' => $approved,
                'message' => $responseMessage,
                'status_code' => $approved ? 200 : 400,
                'data' => [
                    'payment_order_id' => $paymentOrder->id,
                    'transaction_reference' => $transaction->transaction_reference,
                    'status' => $transaction->status,
                    'response_code' => $transaction->response_code,
                    'response_message' => $transaction->response_message,
                    'processed_at' => $transaction->processed_at
                ]
            ];
        });
    }

    /**
     * Get Transaction History
     */
    public function getTransactionHistory($merchant,int $paymentOrderId): array
    {
        $paymentOrder = PaymentOrder::where('id', $paymentOrderId)->where('merchant_id', $merchant->id)->first();

        if (!$paymentOrder) {
            return [
                'success' => false,
                'message' => 'Payment Order not found.',
                'status_code' => 404
            ];
        }

        $transactions = $this->repository->getTransactionHistory($paymentOrder);
        return [
            'success' => true,
            'message' => 'Transaction history retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'payment_order_id' => $paymentOrder->id,
                'order_reference' => $paymentOrder->order_reference,
                'transactions' => $transactions
            ]
        ];
    }
}
