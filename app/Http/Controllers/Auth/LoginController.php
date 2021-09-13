<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Resources\UserResource;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;

class LoginController extends RespondsWithHttpStatusController
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

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages(['message' => trans('auth.failed')]);
        }

        if ($user->email_verified_at == null) {
            throw ValidationException::withMessages(['message' => 'Please verify your email']);
        }

        $user->tokens()->delete();

        $token = $user->createToken(config('auth.token.name'),['admin']);

        $user->tokens()->update(['expires_in' =>  $token->accessToken->created_at->addMinute(config('sanctum.expiration'))]);

        return  $this->respond([
            'user'         => new UserResource($user->load(['roles'])),
            'token'        => $token->plainTextToken,
            'expires_in'   => now()->parse($token->accessToken->created_at)->diffInSeconds($user->expiration($token->accessToken->id))
        ]);
    }
}
