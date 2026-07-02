<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessPaymentRequest;
use App\Managers\PaymentTransactionManager;
use Illuminate\Http\JsonResponse;

class PaymentTransactionController extends Controller
{
    protected PaymentTransactionManager $paymentTransactionManager;

    public function __construct(PaymentTransactionManager $paymentTransactionManager)
    {
        $this->paymentTransactionManager = $paymentTransactionManager;
    }

    /**
     * Process Customer Payment
     */
    public function processPayment(ProcessPaymentRequest $request,int $paymentOrder): JsonResponse
    {
        $response = $this->paymentTransactionManager->processPayment(auth()->user(),$paymentOrder,$request->validated());
        return response()->json($response,$response['status_code']);
    }

    /**
     * Transaction History
     */
    public function history(int $paymentOrder)
    {
        $response = $this->paymentTransactionManager->getTransactionHistory(auth()->user(),$paymentOrder);
        return response()->json($response,$response['status_code']);
    }
}
