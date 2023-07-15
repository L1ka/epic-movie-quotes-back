<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Register\RegisterRequest;
use App\Http\Requests\Login\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {

        $newUser = User::create([
            ...$request->validated(),
            'verification_token' => Str::random(40)
        ]);

        $newUser->sendEmailVerificationNotification();


        return response()->json(['user' => $newUser], 200);
    }

    public function login(LoginRequest $request): JsonResponse
    {

        app()->setLocale($request->getPreferredLanguage());

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'first_name';

        if (!auth()->attempt([$fieldType => $request->email, 'password' => $request->password])) {

            return response()->json([ 'errors' =>  __("wrong_credentials")], 400);
        }

        if (auth()->user()->email_verified_at === null) {
            return response()->json([ 'errors' =>  'not verified'], 401);
        }


        Auth::login(Auth::user(), $request->remember);
        session()->regenerate();
        return response()->json([ 'user' =>  Auth::user()], 200);
    }


    public function logout(Request $request): JsonResponse
    {
        $request->session()->invalidate();
        Auth::guard('web')->logout();

        return response()->json(['message' => 'Logged out successfully']);
    }


    public function verify(Request $request): JsonResponse
    {

        $user = User::where('verification_token', $request->input('token'))
        ->first();

        if (!$user->email_verified_at && Carbon::parse($user->created_at)->addMinutes(2)->isPast()) {
            return response()->json(['error' => 'expired link'], 400);
        }

        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
            return response()->json(['success' => 'email verified successfully'], 200);
        }

        if($user->email_verified_at) {
            return response()->json(['error' => 'already verified'], 400);
        }

    }

    public function sendEmail(Request $request): void
    {
        $user = User::where('verification_token', $request->input('token'))
        ->first();

        $user->created_at = Carbon::now();
        $user->save();
        $user->sendEmailVerificationNotification();
        return;
    }

    public function authUser(): UserResource
    {
        return new UserResource(Auth::user());
    }


}
