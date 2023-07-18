<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['controller' => AuthController::class], function () {
	Route::post('register', 'register')->name('register');
	Route::post('login', 'login')->name('login');
	Route::post('logout', 'logout')->name('logout');
	Route::post('/email-verified', 'verify')->name('email-verification');
	Route::post('/send-email', 'sendEmail')->name('send-email');
	Route::get('/user', 'authUser')->name('get-user');
});

Route::group(['controller' => ResetPasswordController::class], function () {
	Route::post('/forgot-password', 'resetEmail')->name('reset-password');
	Route::post('/reset-password', 'update')->name('password.update');
});

Route::group(['controller' => GoogleAuthController::class], function () {
	Route::get('/auth/google/redirect', 'redirect')->name('google-redirect');
	Route::get('/auth/google/callback', 'callback')->name('google-callback')->middleware('web');
});

Route::middleware('auth:sanctum')->group(function () {
	Route::group(['controller' => QuoteController::class], function () {
		Route::post('/quotes', 'store')->name('quote.store');
		Route::patch('/quotes/{quote}', 'update')->name('quote.update');
		Route::delete('/quote/{quote}', 'delete')->name('quote.delete');
		Route::get('/quote/{quote}', 'show')->name('quote.show');
		Route::get('/quotes/{movie}', 'showQuotes')->name('quotes.show');
	});

	Route::group(['controller' => MovieController::class], function () {
		Route::post('/movies', 'store')->name('movie.store');
		Route::patch('/movies/{movie}', 'update')->name('movie.update');
		Route::delete('/movie/{movie}', 'delete')->name('movie.delete');
		Route::get('/movies/{movie}', 'show')->name('movie.show');
		Route::get('/movies', 'showMovies')->name('movies.show');
		Route::get('/genres', 'showGenres')->name('genres.show');
	});

	Route::group(['controller' => InteractionController::class], function () {
		Route::post('/comment', 'addComment')->name('create-comment');
		Route::post('/like', 'addLike')->name('create-like');
		Route::get('/notifications', 'show')->name('notifications.show');
		Route::post('notification-seen', 'notificationSeen')->name('notification-seen');
		Route::post('mark-all-seen', 'markAllSeen')->name('mark-all-seen');
	});

	Route::group(['controller' => UserProfileController::class], function () {
		Route::post('/profile/user', 'updateUser')->name('update-user');
		Route::post('/profile/image', 'uploadImage')->name('upload-image');
		Route::post('/profile/email', 'updateEmail')->name('update-email');
	});

	Route::group(['controller' => SearchController::class], function () {
		Route::post('search', 'search')->name('search');
		Route::post('search-my-movies', 'searchMyMovies')->name('search-my-movies');
	});
});
