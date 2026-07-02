<?php

namespace App\Managers;

use App\Models\Customer;
use App\Models\PaymentOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentOrderManager
{
    public function createPaymentOrder($merchant, array $data)
    {
        return DB::transaction(function () use ($merchant, $data) {
            //check customer exists or not
            $customer = Customer::where('email',$data['customer']['email'])->first();
            if (!$customer) {
                //create customer
                $customer = Customer::create([
                    'first_name' => $data['customer']['first_name'],
                    'last_name' => $data['customer']['last_name'],
                    'email' => $data['customer']['email'],
                    'phone' => $data['customer']['phone'],
                    'address' => $data['customer']['address'],
                    'city' => $data['customer']['city'],
                    'country' => $data['customer']['country']
                ]);
            }
            //create payment order
            $paymentOrder = PaymentOrder::create([
                'merchant_id' => $merchant->id,
                'customer_id' => $customer->id,
                'order_reference' => 'ORD-' . strtoupper(substr(Str::orderedUuid()->toString(), 0, 12)),
                'amount' => $data['amount'],
                'currency' => strtoupper($data['currency']),
                'description' => $data['description'],
                'status' => config('payment.order_status.pending')
            ]);
            return $paymentOrder->load('customer');
        });
    }

    /**
     * Get Payment Order Details
     */
    public function getPaymentOrder($merchant,int $paymentOrderId): array
    {
        $paymentOrder = PaymentOrder::with(['merchant','customer','transactions'])
                                    ->where('merchant_id', $merchant->id)
                                    ->where('id', $paymentOrderId)
                                    ->first();

        if (!$paymentOrder) {
            return [
                'success' => false,
                'message' => 'Payment Order not found.',
                'status_code' => 404
            ];
        }
        return [
            'success' => true,
            'message' => 'Payment Order retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'id' => $paymentOrder->id,
                'order_reference' => $paymentOrder->order_reference,
                'amount' => $paymentOrder->amount,
                'currency' => $paymentOrder->currency,
                'description' => $paymentOrder->description,
                'status' => $paymentOrder->status,
                'merchant' => [
                    'id' => $paymentOrder->merchant->id,
                    'name' => $paymentOrder->merchant->name,
                    'email' => $paymentOrder->merchant->email,
                ],
                'customer' => [
                    'id' => $paymentOrder->customer->id,
                    'first_name' => $paymentOrder->customer->first_name,
                    'last_name' => $paymentOrder->customer->last_name,
                    'email' => $paymentOrder->customer->email,
                    'phone' => $paymentOrder->customer->phone,
                    'address' => $paymentOrder->customer->address,
                    'city' => $paymentOrder->customer->city,
                    'country' => $paymentOrder->customer->country,
                ],
                'transactions' => $paymentOrder->transactions
            ]
        ];
    }
}
