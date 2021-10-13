<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Enums\VerificationEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\RegistrationFormRequest;
use App\Http\Resources\UserResource;
use App\Models\Token;
use App\Models\User;
use App\Notifications\SendEmailTokenNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends RespondsWithHttpStatusController
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function __invoke(RegistrationFormRequest $request)
    {
        Token::where('email', $request->email)->where('type', VerificationEnum::Email)->delete();

        $password = generateTempPassword();

        $user = User::create([
             'first_name'   => $request->first_name,
             'last_name'    => $request->last_name,
             'email'        => $request->email,
             'gender'       => $request->gender,
             'telephone'    => $request->telephone,
             'password'     => Hash::make($password),
             'status'       => StatusEnum::ACTIVE
        ]);

        $user->assignRole($request->input('roles'));

        $tokenData = Token::create([
            'token' =>  generateToken(),
            'email' => $request->email,
            'type' => VerificationEnum::Email
        ]);

        $user->notify(new SendEmailTokenNotification($tokenData->token, $password));

        return  $this->responseOk([
            'message' => 'Registration successful, An email has been sent to the user',
        ]);

    }




}
