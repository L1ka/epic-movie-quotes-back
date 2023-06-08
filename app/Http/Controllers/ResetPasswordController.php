<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use App\Http\Requests\Password\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ResetPasswordController extends Controller
{
    public function resetEmail(Request $request): JsonResponse
    {
        app()->setLocale($request->getPreferredLanguage());
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['sent' => 'email was sent'], 200)
            : response()->json(['error' =>  __("wrong_email_address")], 400);
    }




    public function update(ResetPasswordRequest $request): JsonResponse
    {

        $status = Password::reset(
            $request->only('email', 'password', 'confirm_password', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
        ? response()->json(['success' => 'success'], 200)
        : response()->json(['error' =>  'error'], 400);
    }

}
