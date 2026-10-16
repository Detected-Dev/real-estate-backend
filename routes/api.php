<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgencyDashboardController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyImageController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\PropertyRequestController;
use App\Http\Controllers\PropertyVisitController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use Illuminate\Image\Transformations\Rotate;

Route::apiResource('users', UserController::class);

Route::apiResource('agencies', AgencyController::class);


Route::apiResource('properties', PropertyController::class);



Route::apiResource('property-images', PropertyImageController::class);



Route::apiResource('property-types', PropertyTypeController::class);


Route::apiResource('property-requests', PropertyRequestController::class);


Route::apiResource('property-visits', PropertyVisitController::class);


Route::apiResource('transactions', TransactionController::class);



Route::apiResource('payments', PaymentController::class);



Route::apiResource('reviews', ReviewController::class);


Route::middleware("")->group(function () {

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

//solo routes functions
Route::get(
    '/properties/{property}/my-visit',
    [PropertyVisitController::class, 'myVisit']
);
Route::middleware('auth')->group(function () {
    Route::get(
        '/properties/{property}/my-request',
        [PropertyRequestController::class , 'myRequest']
    );
});
Route::middleware('auth')->group(function () {
    Route::get(
        '/properties/{property}/my-transaction',
        [TransactionController::class , 'myTransaction']
    );
});

// Agency Dashboard :
Route::middleware('auth')->group(function () {
    Route::get('/agency/dashboard' , [AgencyDashboardController::class , 'index']);
});

Route::middleware('auth')->group(function (){
    Route::patch('/agency/propertyRequests/{propertyRequest}/accept' , [PropertyRequestController::class , 'accept']);
    Route::patch('/agency/propertyRequests/{propertyRequest}/reject' , [PropertyRequestController::class , 'reject']);


    Route::patch('/agency/propertyVisits/{propertyVisit}/accept' , [PropertyVisitController::class , 'accept']);
    Route::patch('/agency/propertyVisits/{propertyVisit}/reject' , [PropertyVisitController::class , 'reject']);
});

// GEMINI CHATBOOT :
Route::post('/chat', [ChatController::class, 'send'])->middleware('throttle:20,1');
