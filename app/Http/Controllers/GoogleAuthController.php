<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;

class GoogleAuthController extends Controller
{
    public function redirect(): JsonResponse
    {
        try {
            $url =  Socialite::driver('google')->redirect()->getTargetUrl();
            return response()->json(['url'=> $url], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate([
            'email' => $googleUser->email,
        ], [
            'first_name' => $googleUser->name,
            'email' => $googleUser->email,
            'password' => Str::random(10),
            'google_id' => $googleUser->id,
            'verification_token' => Str::random(40)
        ]);

        auth()->login($user);
        session()->regenerate();

        return redirect()->to(Config::get('app.front_url').'/news-feed');
    }
}
