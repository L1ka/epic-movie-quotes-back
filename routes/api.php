<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserProfileController;
use App\Http\Resources\MovieResource;
use App\Http\Resources\QuoteResource;
use App\Http\Resources\UserResource;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Mock;

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
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);


Route::post('/email-verified', [AuthController::class, 'verify'])
    ->name('email-verification');

Route::post('/send-email', [AuthController::class, 'sendEmail'])
    ->name('send-email');



Route::post('/forgot-password', [ResetPasswordController::class, 'resetEmail'])->name('reset-password');
Route::post('/reset-password', [ResetPasswordController::class, 'update'])->name('password.update');



Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google-redirect');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google-callback')->middleware('web');


Route::post('/user', function () {

   // return response()->json(['user' => Auth::user()]);
   return new UserResource(Auth::user());
});


Route::post('/update-user', [UserProfileController::class, 'updateUser'])->name('update-user');

Route::post('/upload-image', [UserProfileController::class, 'uploadImage'])->name('upload-image');


Route::get('/get-genres', function () {

    return response()->json(['genres' => Genre::all()]);

});

Route::post('/movie/create', [MovieController::class, 'create'])->name('movie.create');
Route::post('/movie/update', [MovieController::class, 'update'])->name('movie.update');
Route::post('/movie/delete', [MovieController::class, 'delete'])->name('movie.delete');


Route::post('/get-movie', function (Request $request) {

    $movie = Movie::where('id', $request->input('id'))
    ->first();

    return new MovieResource($movie);
});


Route::get('/get-movies', function (Request $request) {

    app()->setLocale($request->getPreferredLanguage());

   return MovieResource::collection(Movie::all());
});

Route::post('/get-quotes', function (Request $request) {

    app()->setLocale($request->getPreferredLanguage());

    $quotes = Movie::where('id', $request->input('id'))
    ->first()->quotes;

    return QuoteResource::collection($quotes);
});

Route::post('/get-quote', function (Request $request) {

    app()->setLocale($request->getPreferredLanguage());

    $quote = Quote::where('id', $request->input('id'))
    ->first();

    return new QuoteResource($quote);
});


Route::post('/quote/create', [QuoteController::class, 'create'])->name('quote.create');
Route::post('/quote/update', [QuoteController::class, 'update'])->name('quote.update');
Route::post('/quote/delete', [QuoteController::class, 'delete'])->name('quote.delete');
