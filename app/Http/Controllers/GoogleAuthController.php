<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        try {
            $url =  Socialite::driver('google')->redirect()->getTargetUrl();
            return response()->json(['url'=> $url], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $image='/storage/thumbnails/test.png';

        $user = User::updateOrCreate([
            'email' => $googleUser->email,
        ], [
            'first_name' => $googleUser->name,
            'email' => $googleUser->email,
            'password' => Str::random(10),
            'google_id' => $googleUser->id,
            'image' => $image
        ]);

        Auth::login($user);
        session()->regenerate();


        return redirect()->to('http://localhost:5173');
    }
}
