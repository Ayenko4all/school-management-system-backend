<?php

namespace App\Http\Controllers\Auth;

use App\Enums\VerificationEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\VerifyTokenFormRequest;
use App\Http\Resources\UserResource;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TokenVerificationController extends RespondsWithHttpStatusController
{
    public function __invoke(VerifyTokenFormRequest $request)
    {

        $token = Token::where('token', $request->token)
            ->where('email', $request->email)->where('type', $request->type)->first();

        if (! $token) {
            throw ValidationException::withMessages(['token' => 'Token is invalid']);
        }

        if ($token->type == VerificationEnum::Email && Carbon::parse($token->created_at)->addMinutes(config('auth.verification.email.expire'))->isPast()) {
            throw ValidationException::withMessages(['token' => 'Token has expired']);
        }

        if ($token->type == VerificationEnum::PASSWORD && Carbon::parse($token->created_at)->addMinutes(config('auth.passwords.users.token'))->isPast()) {
            throw ValidationException::withMessages(['token' => 'Token has expired']);
        }

        Token::where(['email' => $request->email,'token' => $request->token])
            ->update([ 'verified'  => true]);

        if($token->type === VerificationEnum::Email){

            User::where('email', '=', $request->email)
                ->where('email_verified_at', '=', null)
                ->update(['email_verified_at' => now()]);

            $token->delete();
        }

        return  $this->responseOk(['message' => 'Your token has been verified successfully']);
    }
}
