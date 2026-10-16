<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'messages' => 'required|array|max:20',
            'messages.*.role' => 'required|in:user,model',
            'messages.*.text' => 'required|string|max:2000',
        ]);



        $properties = Property::with([
            'propertyType',
            'agency',
        ])
            ->where('status', 'available')
            ->get();



        $propertyContext = $properties->map(function ($property) {
            return [
                'id' => $property->id,
                'title' => $property->title,
                'type' => $property->propertyType?->name,
                'transaction_type' => $property->transaction_type,
                'price' => $property->price,
                'city' => $property->city,
                'address' => $property->address,
                'postal_code' => $property->postal_code,
                'surface' => $property->surface,
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'floors' => $property->floors,
                'description' => $property->description,
                'agency' => $property->agency?->name,
            ];
        })->toJson(JSON_PRETTY_PRINT);


        $contents = collect($data['messages'])
            ->map(function ($message) {
                return [
                    'role' => $message['role'],
                    'parts' => [
                        [
                            'text' => $message['text'],
                        ],
                    ],
                ];
            })
            ->values()
            ->all();


        $systemPrompt = <<<TXT
            You are a friendly assistant for IMMOHORIZON, a real estate agency.

            Your job is to help visitors find properties and answer questions about
            buying, renting, and available properties.

            IMPORTANT:
            You have access to the CURRENT available properties below.

            CURRENT AVAILABLE PROPERTIES:
            {$propertyContext}

            RULES:

            1. Only use the property information provided above.

            2. NEVER invent:
            - properties
            - prices
            - cities
            - addresses
            - availability
            - property characteristics

            3. If the visitor asks how many available properties exist in a specific
            city, count the matching properties from the provided property data.

            4. If the visitor asks for properties in a specific city, only show properties
            whose city matches that city.

            5. If the visitor asks for properties for sale, only show properties where
            transaction_type is "sale".

            6. If the visitor asks for properties for rent, only show properties where
            transaction_type is "rent".

            7. If no property matches the visitor's request, clearly tell them that there
            are currently no available properties matching their criteria.

            8. When showing a property, provide useful information such as:
            - title
            - type
            - price
            - city
            - address
            - surface
            - bedrooms
            - bathrooms
            - transaction type

            9. When a visitor shows serious interest in a property, ask for:
            - name
            - phone number
            - budget
            - preferred area

            10. For negotiations, contracts, legal questions, or decisions that require
                an agent, tell the visitor that an agent will contact them.

            11. Reply in the same language as the visitor.

            12. Keep answers clear, natural, friendly, and reasonably concise.

            TXT;



        $models = array_unique([
            config('services.gemini.model'),
            'gemini-3.7-flash',
            'gemini-3.6-flash',
            'gemini-3.5-flash-lite',
            'gemini-2.5-flash-lite',
        ]);

        $response = null;
        $lastError = null;


        foreach ($models as $model) {

            Log::info("Trying Gemini model: {$model}");

            $response = Http::withHeaders([
                'x-goog-api-key' => config('services.gemini.key'),
                'Content-Type' => 'application/json',
            ])
                ->timeout(30)
                ->retry(
                    1,
                    1000,
                    throw: false
                )
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
                    [
                        'system_instruction' => [
                            'parts' => [
                                [
                                    'text' => $systemPrompt,
                                ],
                            ],
                        ],

                        'contents' => $contents,
                    ]
                );



            if ($response->successful()) {

                Log::info("Gemini model {$model} succeeded.");

                break;
            }


            $lastError = [
                'model' => $model,
                'status' => $response->status(),
                'body' => $response->body(),
            ];

            Log::warning(
                "Gemini model {$model} failed.",
                $lastError
            );
        }


        if (!$response || $response->failed()) {

            $status = $response?->status();

            if ($status === 503) {

                $message =
                    'The AI assistant is currently busy. Please try again in a moment.';
            } elseif ($status === 429) {

                $message =
                    'The AI assistant is temporarily rate-limited. Please try again shortly.';
            } elseif ($status === 401 || $status === 403) {

                $message =
                    'The AI assistant is currently unavailable.';
            } else {

                $message =
                    'Sorry, something went wrong. Please try again.';
            }

            return response()->json([
                'reply' => $message,

                'debug' => config('app.debug')
                    ? $lastError
                    : null,
            ], 500);
        }



        $reply = $response->json(
            'candidates.0.content.parts.0.text'
        );


        if (!$reply) {

            Log::warning(
                'Gemini returned an unexpected response.',
                [
                    'body' => $response->body(),
                ]
            );

            return response()->json([
                'reply' => 'Sorry, I could not generate an answer.',
            ], 500);
        }


        return response()->json([
            'reply' => $reply,
        ]);
    }
}
