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
     *  @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RegistrationFormRequest $request)
    {
        Token::where('email', $request->email)->where('type', VerificationEnum::Email)->delete();

         User::create([
             'first_name'   => $request->first_name,
             'last_name'    => $request->last_name,
             'email'        => $request->email,
             'gender'       => $request->gender,
             'telephone'    => $request->telephone,
             'password'     => Hash::make($request->password),
             'status'       => StatusEnum::ACTIVE
        ])->assignRoleToUser([RoleEnum::USER]);

        $tokenData = Token::create([
            'token' =>  generateToken(),
            'email' => $request->email,
            'type' => VerificationEnum::Email
        ]);

        \Notification::route('mail', $request->email)->notify(new SendEmailTokenNotification($tokenData->token));

        return  $this->responseOk([
            'message' => 'Registration successful, Please check your email for a verification code',
        ]);

    }




}
