<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserProfileController;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;

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

    return response()->json(['user' => Auth::user()]);

});


Route::post('/update-user', [UserProfileController::class, 'updateUser'])->name('update-user');

Route::post('/upload-image', [UserProfileController::class, 'uploadImage'])->name('upload-image');


Route::get('/get-genres', function () {

    return response()->json(['genres' => Genre::all()]);

});

Route::post('/movie/create', [MovieController::class, 'create'])->name('movie.create');
Route::post('/movie/update', [MovieController::class, 'update'])->name('movie.update');
