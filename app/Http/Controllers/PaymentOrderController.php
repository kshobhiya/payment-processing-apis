<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentOrderRequest;
use App\Managers\PaymentOrderManager;

class PaymentOrderController extends Controller
{
    protected PaymentOrderManager $paymentOrderManager;

    public function __construct(PaymentOrderManager $paymentOrderManager)
    {
        $this->paymentOrderManager = $paymentOrderManager;
    }

    public function store(StorePaymentOrderRequest $request)
    {
        $paymentOrder = $this->paymentOrderManager->createPaymentOrder(auth()->user(),$request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Payment order created successfully.',
            'data' => $paymentOrder
        ], 201);
    }

    /**
     * Get Payment Order Details
     */
    public function show(int $paymentOrder)
    {
        $response = $this->paymentOrderManager->getPaymentOrder(auth()->user(),$paymentOrder);
        return response()->json($response,$response['status_code']);
    }
}
