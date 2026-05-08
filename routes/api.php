<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ServiceCategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SuggestionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OpeningHourController;
use App\Http\Controllers\PlaceCategoryController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Public
Route::get('place-categories', [PlaceCategoryController::class, 'index']);
Route::get('place-categories/{placeCategory}', [PlaceCategoryController::class, 'show']);
Route::get('service-categories', [ServiceCategoryController::class, 'index']);
Route::get('service-categories/{serviceCategory}', [ServiceCategoryController::class, 'show']);

Route::get('places', [PlaceController::class, 'index']);
Route::get('places/{place}', [PlaceController::class, 'show']);
Route::get('services', [ServiceController::class, 'index']);
Route::get('services/{service}', [ServiceController::class, 'show']);
Route::get('places/{place}/reviews', [ReviewController::class, 'index']);
Route::get('places/{place}/reviews/{review}', [ReviewController::class, 'show']);
Route::get('places/{place}/opening-hours', [OpeningHourController::class, 'index']);
Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{post:slug}', [PostController::class, 'show']);
Route::post('contact-messages', [ContactMessageController::class, 'store']);
Route::post('suggestions/place', [SuggestionController::class, 'storePlace']);
Route::post('suggestions/service', [SuggestionController::class, 'storeService']);

// Authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::put('profile', [ProfileController::class, 'update']);

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

    // My suggestions
    Route::get('my-suggestions/places', [SuggestionController::class, 'myPlaceSuggestions']);
    Route::get('my-suggestions/services', [SuggestionController::class, 'myServiceSuggestions']);

    // Admin only
    Route::middleware('can:admin')->group(function () {
        Route::post('place-categories', [PlaceCategoryController::class, 'store']);
        Route::put('place-categories/{placeCategory}', [PlaceCategoryController::class, 'update']);
        Route::delete('place-categories/{placeCategory}', [PlaceCategoryController::class, 'destroy']);
        Route::get('admin/suggestions/places', [SuggestionController::class, 'adminPlaceQueue']);
        Route::get('admin/suggestions/services', [SuggestionController::class, 'adminServiceQueue']);
    });
});
