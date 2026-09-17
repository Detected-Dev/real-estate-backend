<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display all payments.
     */
    public function index()
    {
        $payments = Payment::all();

        return response()->json([
            'message' => 'Paiements récupérés avec succès.',
            'data' => $payments
        ], 200);
    }

    /**
     * Store a new payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,bank_transfer,card,online',
            'status' => 'sometimes|string',
            'paid_at' => 'nullable|date',
            'transaction_reference' => 'nullable|string|max:255',
        ]);

        $payment = Payment::create($validated);

        return response()->json([
            'message' => 'Paiement créé avec succès.',
            'data' => $payment
        ], 201);
    }

    /**
     * Display one payment.
     */
    public function show(Payment $payment)
    {
        return response()->json([
            'message' => 'Paiement récupéré avec succès.',
            'data' => $payment
        ], 200);
    }

    /**
     * Update a payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'transaction_id' => 'sometimes|exists:transactions,id',
            'amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'sometimes|string|in:cash,bank_transfer,card,online',
            'status' => 'sometimes|string',
            'paid_at' => 'nullable|date',
            'transaction_reference' => 'nullable|string|max:255',
        ]);

        $payment->update($validated);

        return response()->json([
            'message' => 'Paiement modifié avec succès.',
            'data' => $payment
        ], 200);
    }

    /**
     * Delete a payment.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json([
            'message' => 'Paiement supprimé avec succès.'
        ], 200);
    }
}
