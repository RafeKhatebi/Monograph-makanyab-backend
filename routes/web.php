<?php

use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\FavoriteWebController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PlaceController;
use App\Http\Controllers\Frontend\UserProfileController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard (redirect to home for authenticated users)
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

// Places
Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
Route::get('/places/{place:slug}', [PlaceController::class, 'show'])->name('places.show');

// Reviews (auth required)
Route::post('/places/{place:slug}/reviews', [PlaceController::class, 'storeReview'])
    ->middleware('auth')
    ->name('places.reviews.store');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Auth-protected routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // Favorites
    Route::get('/favorites', [FavoriteWebController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [FavoriteWebController::class, 'toggle'])->name('favorites.toggle');
});

// Keep Breeze profile edit route for password/account management
Route::middleware('auth')->group(function () {
    Route::get('/account', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('/account', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
