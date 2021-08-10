<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Enums\VerificationEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationFormRequest;
use App\Http\Resources\UserResource;
use App\Models\Token;
use App\Models\User;
use App\Notifications\SendEmailTokenNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     *  @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RegistrationFormRequest $request)
    {
        Token::where('email', $request->email)->where('type', VerificationEnum::VERIFICATION)->delete();

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
            'token' =>  $this->generateToken(),
            'email' => $request->email,
            'type' => VerificationEnum::VERIFICATION
        ]);

        \Notification::route('mail', $request->email)->notify(new SendEmailTokenNotification($tokenData->token));

        return  response()->json([
            'status' => 'success',
            'body' => 'Registration successful, Please check your email for a verification code',
        ], 201);

    }

    protected function generateToken()
    {
        do {
            $token = mt_rand(100000, 999999);
        } while (Token::where('token', $token)->exists());

        return (string) $token;
    }


}
