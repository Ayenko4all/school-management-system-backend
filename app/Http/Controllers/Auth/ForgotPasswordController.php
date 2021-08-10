<?php

namespace App\Http\Controllers\Auth;

use App\Enums\VerificationEnum;
use App\Http\Controllers\Controller;
use App\Notifications\ForgotPasswordNotification;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function  __invoke(Request $request)
    {
        $request->validate(['email' => ['required', 'email', 'exists:users']]);

        $user = User::where('email', $request->email)->firstOrFail();

        $tokenData = Token::updateOrCreate(
            ['email' => $user->email,'type'  => VerificationEnum::PASSWORD],
            [
            'email' => $user->email,
            'token' => $this->generateToken(),
            'type'  => VerificationEnum::PASSWORD
            ]
        );

        $user->notify(new ForgotPasswordNotification($tokenData->token));

        return response()->json([
            'status' => 'success',
            'body' => 'Password reset token sent successfully'
        ],200);
    }

    protected function generateToken()
    {
        do {
            $token = mt_rand(100000, 999999);
        } while (Token::where('token', $token)->exists());

        return (string) $token;
    }
}
