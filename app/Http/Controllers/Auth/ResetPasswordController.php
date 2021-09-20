<?php

namespace App\Http\Controllers\Auth;

use App\Enums\VerificationEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\VerifyPasswordResetRequest;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends RespondsWithHttpStatusController
{
    public function __invoke(VerifyPasswordResetRequest $request)
    {

        $token = Token::where('token', $request->token)
            ->where('verified', false)
            ->where('type', 'password')
            ->first();

        if (! $token || Carbon::parse($token->created_at)->addMinutes(config('auth.passwords.users.token'))->isPast()) {
            throw ValidationException::withMessages(['token' => 'Token is invalid or has expired']);
        }

        User::where('email', $token->email)->update(['password' => Hash::make($request->password)]);

        $token->delete();

        return $this->responseOk(['message' => 'Password was updated successfully.']);
    }
}
