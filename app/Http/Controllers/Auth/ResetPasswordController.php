<?php

namespace App\Http\Controllers\Auth;

use App\Enums\VerificationEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends RespondsWithHttpStatusController
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'token'     =>  ['required', 'digits:6', 'exists:tokens,token']
        ]);

        $token = Token::where(['token' => $request->token,'type' => VerificationEnum::PASSWORD ,'verified' => true])->first();

        if (! $token) {
            throw ValidationException::withMessages(['token' => 'Token is invalid']);
        }

        User::where('email', $token->email)->update(['password' => Hash::make($request->password)]);

        $token->delete();

        return $this->responseOk((string)['message' => 'Password was successfully reset']);
    }
}
