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
    Route::post('register',  'register')->name('register');
    Route::post('login',  'login')->name('login');
    Route::post('logout',  'logout')->name('logout');
    Route::post('/email-verified',  'verify')->name('email-verification');
    Route::post('/send-email',  'sendEmail')->name('send-email');
    Route::get('/user',  'authUser')->name('get-user');
});

Route::group(['controller' => ResetPasswordController::class], function () {
    Route::post('/forgot-password', 'resetEmail')->name('reset-password');
    Route::post('/reset-password', 'update')->name('password.update');
});


Route::group(['controller' => GoogleAuthController::class], function () {
    Route::get('/auth/google/redirect', 'redirect')->name('google-redirect');
    Route::get('/auth/google/callback', 'callback')->name('google-callback')->middleware('web');
});



Route::middleware('auth:sanctum')->group(function() {
    Route::group(['controller' => QuoteController::class], function () {
        Route::post('quote/store',  'store')->name('quote.store');
        Route::post('quote/update',  'update')->name('quote.update');
        Route::delete('quote/delete/{id}',  'delete')->name('quote.delete');
        Route::get('get-quote/{id}',  'getQuote')->name('get-quote');
        Route::get('get-quotes/{id}',  'getQuotes')->name('get-quotes');
    });

    Route::group(['controller' => MovieController::class], function () {
        Route::post('/movie/create', 'create')->name('movie.create');
        Route::post('/movie/update','update')->name('movie.update');
        Route::delete('/movie/delete/{id}',  'delete')->name('movie.delete');
        Route::get('/get-movie/{id}',  'getMovie')->name('get-movie');
        Route::get('/get-movies',  'getMovies')->name('get-movies');
        Route::get('/get-genres',  'getGenres')->name('get-genres');
    });



    Route::group(['controller' => InteractionController::class], function () {
        Route::post('create-comment', 'addComment')->name('create-comment');
        Route::post('create-like', 'addLike')->name('create-like');
        Route::get('get-notifications', 'getNotifications')->name('get-notifications');
        Route::post('notification-seen', 'notificationSeen')->name('notification-seen');
        Route::post('mark-all-seen', 'MarkAllSeen')->name('mark-all-seen');
    });


    Route::group(['controller' => UserProfileController::class], function () {
        Route::post('/update-user',  'updateUser')->name('update-user');
        Route::post('/upload-image',  'uploadImage')->name('upload-image');
        Route::post('/update-email',  'updateEmail')->name('update-email');
    });

    Route::group(['controller' => SearchController::class], function () {
        Route::post('search',  'search')->name('search');
        Route::post('search-my-movies',  'searchMyMovies')->name('search-my-movies');
    });



});



