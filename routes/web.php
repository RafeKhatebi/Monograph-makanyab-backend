<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PlaceCategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\FavoriteWebController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PlaceController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\UserProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// About
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Search Section
|--------------------------------------------------------------------------
*/
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

/*
|--------------------------------------------------------------------------
| Posts Section
|--------------------------------------------------------------------------
*/
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

/*
|--------------------------------------------------------------------------
| Places
|--------------------------------------------------------------------------
*/
Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
Route::get('/places/{place:slug}', [PlaceController::class, 'show'])->name('places.show');

// Reviews
Route::post('/places/{place:slug}/reviews', [PlaceController::class, 'storeReview'])
    ->middleware('auth')
    ->name('places.reviews.store');

/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {

    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');

})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Auth Protected Routes
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        /*
        |--------------------------------------------------------------
        | Places Management
        |--------------------------------------------------------------
        */
        Route::resource('places', App\Http\Controllers\Admin\PlaceController::class);

        Route::post(
            'places/{place}/toggle-verification',
            [App\Http\Controllers\Admin\PlaceController::class, 'toggleVerification']
        )->name('places.toggle-verification');

        Route::post(
            'places/{place}/toggle-active',
            [App\Http\Controllers\Admin\PlaceController::class, 'toggleActive']
        )->name('places.toggle-active');

        /*
        |--------------------------------------------------------------
        | Categories Management
        |--------------------------------------------------------------
        */
        Route::resource('categories', PlaceCategoryController::class);

        /*
        |--------------------------------------------------------------
        | Search Management
        |--------------------------------------------------------------
        */
        Route::get('/search', [SearchController::class, 'index'])->name('search.index');

        /*
        |--------------------------------------------------------------
        | Users Management
        |--------------------------------------------------------------
        */
        Route::resource('users', UserController::class);

        Route::post(
            'users/{user}/toggle-active',
            [UserController::class, 'toggleActive']
        )->name('users.toggle-active');

        /*
        |--------------------------------------------------------------
        | Reviews Management
        |--------------------------------------------------------------
        */
        Route::resource('reviews', ReviewController::class)
            ->only(['index', 'show', 'destroy']);

        /*
        |--------------------------------------------------------------
        | Posts Management
        |--------------------------------------------------------------
        */
        Route::resource('posts', AdminPostController::class);
    });

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
