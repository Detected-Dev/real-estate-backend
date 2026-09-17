<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyImageController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\PropertyRequestController;
use App\Http\Controllers\PropertyVisitController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Authenticated User
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/

Route::apiResource('users', UserController::class);


/*
|--------------------------------------------------------------------------
| Agencies
|--------------------------------------------------------------------------
*/

Route::apiResource('agencies', AgencyController::class);


/*
|--------------------------------------------------------------------------
| Properties
|--------------------------------------------------------------------------
*/

Route::apiResource('properties', PropertyController::class);


/*
|--------------------------------------------------------------------------
| Property Images
|--------------------------------------------------------------------------
*/

Route::apiResource('property-images', PropertyImageController::class);


/*
|--------------------------------------------------------------------------
| Property Types
|--------------------------------------------------------------------------
*/

Route::apiResource('property-types', PropertyTypeController::class);


/*
|--------------------------------------------------------------------------
| Property Requests
|--------------------------------------------------------------------------
*/

Route::apiResource('property-requests', PropertyRequestController::class);


/*
|--------------------------------------------------------------------------
| Property Visits
|--------------------------------------------------------------------------
*/

Route::apiResource('property-visits', PropertyVisitController::class);


/*
|--------------------------------------------------------------------------
| Transactions
|--------------------------------------------------------------------------
*/

Route::apiResource('transactions', TransactionController::class);


/*
|--------------------------------------------------------------------------
| Payments
|--------------------------------------------------------------------------
*/

Route::apiResource('payments', PaymentController::class);


/*
|--------------------------------------------------------------------------
| Reviews
|--------------------------------------------------------------------------
*/

Route::apiResource('reviews', ReviewController::class);


// DON'T TOUCH IT ;/
Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::middleware('auth')->group(function() {
    Route::get('/user' , [AuthController::class , 'user']);
    Route::post('/logout' , [AuthController::class , 'logout']);
});
