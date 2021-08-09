<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Token;

class LoginController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws ValidationException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (! $user || $user->email_verified_at == null) {
            throw ValidationException::withMessages(['email' => 'Please verify your email']);
        }

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages(['email' => trans('auth.failed')]);
        }

        Token::where('user_id', $user->id)->delete();

        return  response()->json([
            'status' => 'success',
            'data' => [
                'user' => new UserResource($user),
                'token'=> User::authAccessToken($request->email)->accessToken
            ]
        ], 200);
    }
}
