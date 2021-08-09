<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Token;

class LogoutController extends Controller
{
    /**
     * Handle a logout request to the application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        Token::where('user_id', auth()->id())->delete();

        return response()->json([]);
    }
}
