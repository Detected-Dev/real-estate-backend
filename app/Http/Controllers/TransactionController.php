<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display all transactions.
     */
    public function index()
    {
        $transactions = Transaction::all();

        return response()->json([
            'message' => 'Transactions récupérées avec succès.',
            'data' => $transactions
        ], 200);
    }

    /**
     * Store a new transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'seller_id' => 'required|exists:users,id',
            'agency_id' => 'required|exists:agencies,id',
            'type' => 'required|string|in:sale,rent',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'nullable|date',
        ]);

        $transaction = Transaction::create([
            'property_id' => $validated['property_id'],
            'seller_id' => $validated['seller_id'],
            'buyer_id' => $request->user()->id,
            'agency_id' => $validated['agency_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'] ?? null,
        ]);

        return response()->json([
            'message' => 'Transaction créée avec succès.',
            'data' => $transaction
        ], 201);
    }

    /**
     * Display one transaction.
     */
    public function show(Transaction $transaction)
    {
        return response()->json([
            'message' => 'Transaction récupérée avec succès.',
            'data' => $transaction
        ], 200);
    }

    /**
     * Update a transaction.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'property_id' => 'sometimes|exists:properties,id',
            'buyer_id' => 'sometimes|exists:users,id',
            'seller_id' => 'sometimes|exists:users,id',
            'agency_id' => 'sometimes|exists:agencies,id',

            'type' => 'sometimes|string|in:sale,rent',

            'amount' => 'sometimes|numeric|min:0',

            'status' => 'sometimes|string|in:pending,in_progress,completed,cancelled',

            'transaction_date' => 'nullable|date',
        ]);

        $transaction->update($validated);

        return response()->json([
            'message' => 'Transaction modifiée avec succès.',
            'data' => $transaction
        ], 200);
    }

    /**
     * Delete a transaction.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json([
            'message' => 'Transaction supprimée avec succès.'
        ], 200);
    }

    public function myTransaction(Property $property , Request $request){
        $transaction = Transaction::where([['property_id' , $property->id],['buyer_id' , $request->user()->id]])->latest()->first();
        return response()->json([
            'data' => $transaction
        ]);
    }
}
