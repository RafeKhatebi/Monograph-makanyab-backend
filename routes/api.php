<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OpeningHourController;
use App\Http\Controllers\PlaceCategoryController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('place-categories', [PlaceCategoryController::class, 'index']);
Route::get('place-categories/{placeCategory}', [PlaceCategoryController::class, 'show']);

Route::get('places', [PlaceController::class, 'index']);
Route::get('places/{place}', [PlaceController::class, 'show']);
Route::get('places/{place}/reviews', [ReviewController::class, 'index']);
Route::get('places/{place}/reviews/{review}', [ReviewController::class, 'show']);
Route::get('places/{place}/opening-hours', [OpeningHourController::class, 'index']);

// Authenticated
Route::middleware('auth:sanctum')->group(function () {

    // Places
    Route::post('places', [PlaceController::class, 'store']);
    Route::put('places/{place}', [PlaceController::class, 'update']);
    Route::patch('places/{place}', [PlaceController::class, 'update']);
    Route::delete('places/{place}', [PlaceController::class, 'destroy']);

    // Reviews
    Route::post('places/{place}/reviews', [ReviewController::class, 'store']);
    Route::delete('places/{place}/reviews/{review}', [ReviewController::class, 'destroy']);

    // Opening hours
    Route::post('places/{place}/opening-hours', [OpeningHourController::class, 'store']);
    Route::put('places/{place}/opening-hours/{openingHour}', [OpeningHourController::class, 'update']);
    Route::delete('places/{place}/opening-hours/{openingHour}', [OpeningHourController::class, 'destroy']);

    // Favorites
    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('favorites', [FavoriteController::class, 'store']);
    Route::get('favorites/{place}/check', [FavoriteController::class, 'check']);
    Route::delete('favorites/{place}', [FavoriteController::class, 'destroy']);

    // Admin only
    Route::middleware('can:admin')->group(function () {
        Route::post('place-categories', [PlaceCategoryController::class, 'store']);
        Route::put('place-categories/{placeCategory}', [PlaceCategoryController::class, 'update']);
        Route::delete('place-categories/{placeCategory}', [PlaceCategoryController::class, 'destroy']);
    });
});
