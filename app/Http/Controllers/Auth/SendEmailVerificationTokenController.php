<?php

namespace App\Http\Controllers\Auth;

use App\Enums\VerificationEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Token;
use App\Models\User;
use App\Notifications\SendEmailTokenNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SendEmailVerificationTokenController extends Controller
{
    public function __invoke(Request $request){
        $request->validate([
            'email'   => ['required','email','exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at != null) {
            throw ValidationException::withMessages(['email' => 'You have already verify your email']);
        }

        $tokenData = Token::updateOrCreate(
            ['email' => $request->email,'type' => VerificationEnum::VERIFICATION],
            [
            'token' =>  $this->generateToken(),
            'email' => $request->email,
            'type' => VerificationEnum::VERIFICATION
        ]);

        \Notification::route('mail', $request->email)->notify(new SendEmailTokenNotification($tokenData->token));

        return  response()->json([
            'status' => 'success',
            'body' => 'Please check your email for a verification code'
        ], 200);
    }



    protected function generateToken()
    {
        do {
            $token = mt_rand(100000, 999999);
        } while (Token::where('token', $token)->exists());

        return (string) $token;
    }
}
