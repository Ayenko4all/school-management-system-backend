<?php

namespace App\Http\Controllers\Auth;

use App\Enums\VerificationEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Resources\UserResource;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailVerificationController extends RespondsWithHttpStatusController
{
    public function __invoke(VerifyEmailRequest $request)
    {

        $token = Token::where('token', $request->token)
            ->where('verified', false)
            ->where('type', 'email')
            ->first();

        if (Carbon::parse($token->created_at)->addMinutes(config('auth.verification.email.expire'))->isPast()) {
            throw ValidationException::withMessages(['token' => 'Token has expired']);
        }

        User::where('email', '=', $token->email)
            ->where('email_verified_at', '=', null)
            ->update(['email_verified_at' => now()]);

        $token->delete();

        return  $this->responseOk(['message' => 'Your email has been verified successfully']);
    }
}
