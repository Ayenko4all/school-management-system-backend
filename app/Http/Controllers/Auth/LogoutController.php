<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;

class LogoutController extends RespondsWithHttpStatusController
{
    /**
     * Handle a logout request to the application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        auth()->user()->tokens()->delete();

        return $this->responseNoContent();
    }
}
