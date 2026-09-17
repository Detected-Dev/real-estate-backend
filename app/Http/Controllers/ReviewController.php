<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display all reviews.
     */
    public function index()
    {
        $reviews = Review::all();

        return response()->json([
            'message' => 'Avis récupérés avec succès.',
            'data' => $reviews
        ], 200);
    }

    /**
     * Store a new review.
     */
    public function store(Request $request)
    {
        $review = Review::create([]);

        return response()->json([
            'message' => 'Avis créé avec succès.',
            'data' => $review
        ], 201);
    }

    /**
     * Display one review.
     */
    public function show(Review $review)
    {
        return response()->json([
            'message' => 'Avis récupéré avec succès.',
            'data' => $review
        ], 200);
    }

    /**
     * Update a review.
     */
    public function update(Request $request, Review $review)
    {
        return response()->json([
            'message' => 'Aucune donnée à modifier pour le moment.',
            'data' => $review
        ], 200);
    }

    /**
     * Delete a review.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json([
            'message' => 'Avis supprimé avec succès.'
        ], 200);
    }
}
