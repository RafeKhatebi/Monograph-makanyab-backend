<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PlaceCategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\FavoriteWebController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PlaceController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\PlaceSuggestionController;
use App\Http\Controllers\Frontend\ServiceSuggestionController;
use App\Http\Controllers\Frontend\ServiceCategoryController as FrontendServiceCategoryController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use App\Http\Controllers\Frontend\UserProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//  Public Routes

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// About
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Search Section
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/suggest-place', [PlaceSuggestionController::class, 'create'])->name('place-suggestions.create');
Route::post('/suggest-place', [PlaceSuggestionController::class, 'store'])->name('place-suggestions.store');
Route::get('/suggest-service', [ServiceSuggestionController::class, 'create'])->name('service-suggestions.create');
Route::post('/suggest-service', [ServiceSuggestionController::class, 'store'])->name('service-suggestions.store');

//  Posts Section

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

// Places
Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
Route::get('/places/{place:slug}', [PlaceController::class, 'show'])->name('places.show');

Route::get('/services/{service:slug}', [FrontendServiceController::class, 'show'])->name('services.show');

// Services Index
Route::get('/services', [FrontendServiceController::class, 'index'])->name('services.index');

//  Service Categories

Route::get('/service-categories', [FrontendServiceCategoryController::class, 'index'])->name('service-categories.index');
Route::get('/service-categories/{slug}', [FrontendServiceCategoryController::class, 'show'])->name('service-categories.show');

// Reviews
Route::post('/places/{place:slug}/reviews', [PlaceController::class, 'storeReview'])
    ->middleware('auth')
    ->name('places.reviews.store');

//  Categories

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

//  Dashboard Redirect

Route::middleware('auth')->get('/dashboard', function () {

    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');

})->name('dashboard');

//  Auth Protected Routes

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // Favorites
    Route::get('/favorites', [FavoriteWebController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [FavoriteWebController::class, 'toggle'])->name('favorites.toggle');

    // Breeze Account
    Route::get('/account', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('/account', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        //  Places Management
        Route::resource('places', App\Http\Controllers\Admin\PlaceController::class);

        Route::post(
            'places/{place}/toggle-verification',
            [App\Http\Controllers\Admin\PlaceController::class, 'toggleVerification']
        )->name('places.toggle-verification');

        Route::post(
            'places/{place}/toggle-active',
            [App\Http\Controllers\Admin\PlaceController::class, 'toggleActive']
        )->name('places.toggle-active');

        //    Categories Management

        Route::resource('categories', PlaceCategoryController::class);
        Route::resource('service-categories', ServiceCategoryController::class);
        //  Services Management

        Route::resource('services', ServiceController::class);

        //    Search Management

        Route::get('/search', [SearchController::class, 'index'])->name('search.index');

        // Users Management

        Route::resource('users', UserController::class);

        Route::post(
            'users/{user}/toggle-active',
            [UserController::class, 'toggleActive']
        )->name('users.toggle-active');

        //  Place Suggestions
        Route::resource('place-suggestions', App\Http\Controllers\Admin\PlaceSuggestionController::class)
            ->only(['index', 'show']);

        Route::post('place-suggestions/{placeSuggestion}/approve', [App\Http\Controllers\Admin\PlaceSuggestionController::class, 'approve'])
            ->name('place-suggestions.approve');

        Route::post('place-suggestions/{placeSuggestion}/reject', [App\Http\Controllers\Admin\PlaceSuggestionController::class, 'reject'])
            ->name('place-suggestions.reject');

        //  Service Suggestions
        Route::resource('service-suggestions', App\Http\Controllers\Admin\ServiceSuggestionController::class)
            ->only(['index', 'show']);

        Route::post('service-suggestions/{serviceSuggestion}/approve', [App\Http\Controllers\Admin\ServiceSuggestionController::class, 'approve'])
            ->name('service-suggestions.approve');

        Route::post('service-suggestions/{serviceSuggestion}/reject', [App\Http\Controllers\Admin\ServiceSuggestionController::class, 'reject'])
            ->name('service-suggestions.reject');

        //  Reviews Management

        Route::resource('reviews', ReviewController::class)
            ->only(['index', 'show', 'destroy']);

        /*
        Posts Management
        */
        Route::resource('posts', AdminPostController::class);
    });

// Fall back route for 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
})->name('fallback');

//  Auth Routes

require __DIR__.'/auth.php';
