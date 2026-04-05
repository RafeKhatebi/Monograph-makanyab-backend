<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\FavoriteWebController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PlaceController;
use App\Http\Controllers\Frontend\UserProfileController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard (redirect based on role)
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->name('dashboard');

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

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Places Management
    Route::resource('places', \App\Http\Controllers\Admin\PlaceController::class);
    Route::post('places/{place}/toggle-verification', [\App\Http\Controllers\Admin\PlaceController::class, 'toggleVerification'])->name('places.toggle-verification');
    Route::post('places/{place}/toggle-active', [\App\Http\Controllers\Admin\PlaceController::class, 'toggleActive'])->name('places.toggle-active');
    
    // Categories Management
    Route::resource('categories', \App\Http\Controllers\Admin\PlaceCategoryController::class);
    
    // Users Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');
    
    // Reviews Management
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'show', 'destroy']);
});

// Keep Breeze profile edit route for password/account management
Route::middleware('auth')->group(function () {
    Route::get('/account', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('/account', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
