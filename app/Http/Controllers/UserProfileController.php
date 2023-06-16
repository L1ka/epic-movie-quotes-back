<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class UserProfileController extends Controller
{
    public function updateUser(Request $request): JsonResponse
    {

        $currentUser = User::where('id', $request->user['id'])
        ->first();

        if (!$currentUser) {
            return response()->json(['error' => 'unauthorized', 401]);
        }

        if($request->email){
            $request->validate([
                'email' => 'unique:users',
            ]);
            $currentUser->email = $request->email;
            $currentUser->email_verified_at = null;
            $currentUser->created_at = Carbon::now();
            $currentUser->save();
            $currentUser->sendEmailVerificationNotification();
        }

        if($request->first_name){
            $request->validate([
                'first_name' => 'unique:users',
            ]);
            $currentUser->first_name = $request->first_name;
            $currentUser->save();
        }

        if($request->password){
            $currentUser->password = $request->password;
            $currentUser->confirm_password = $request->password;
            $currentUser->save();
        }

        return response()->json(['changed' => $currentUser, 200]);
    }

    public function uploadImage(Request $request):JsonResponse
    {
        $user = Auth::user();

        $user->image = '/storage/'.$request->image->store('thumbnails');
        $user->save();

        return response()->json(['image' =>  $user->image, 200])->header('Content-Type', 'multipart/form-data');
    }


}
