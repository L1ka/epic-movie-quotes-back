<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UserProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function updateUser(UserProfileRequest $request): void
    {
        $currentUser = User::where('id', $request->user['id'])
        ->first();


        if($request->has('email')){
            $currentUser->temp_email = $request->email;
            $currentUser->save();
        }

        if($request->has('first_name')){
            $currentUser->first_name = $request->first_name;
            $currentUser->save();
        }

        if($request->has('password')){
            $currentUser->password = $request->password;
            $currentUser->confirm_password = $request->password;
            $currentUser->save();
        }

        if ($request->has('email')) {
            $currentUser->sendEmailVerificationNotification('updateEmail');
        }


    }

    public function uploadImage(Request $request):JsonResponse
    {
        $user = Auth::user();

        $user->image = '/storage/'.$request->image->store('thumbnails');
        $user->save();

        return response()->json(['image' =>  $user->image, 200])->header('Content-Type', 'multipart/form-data');
    }

    public function updateEmail(Request $request): void
    {
        $currentUser = Auth::user();


        $currentUser->email = $request->email;
        $currentUser->save();

    }

}
