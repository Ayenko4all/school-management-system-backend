<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Session\TokenMismatchException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Client\RequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    /**
     * Register the exception handling callbacks for the application.
     *
     *
     */


    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            if ($e instanceof AuthenticationException) {
                 return response()->json([
                     'message' => 'Unauthenticated.'
                 ],Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Resource not found.'
                ],  Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof BadRequestHttpException) {
                return response()->json([
                    'message' => 'Missing parameters.'
                ],  400);
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'message' => 'This method is not supported for this route.'
                ],  405);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => 'Record not found.'
                ], Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof RequestException) {
                return response()->json([
                    'message' => 'The requested URI is invalid.'
                ],  401);
            }

            if ($e instanceof AccessDeniedHttpException) {
                return response()->json([
                    'message' => 'Access denied.'
                ],  403);
            }

            if ($e instanceof FatalError) {
                return response()->json([
                    'message' => 'Server error, something went wrong. Please try again'
                ],  500);
            }

            if ($e instanceof AuthorizationException || $e instanceof UnauthorizedException) {
                return response()->json([
                    'message' => 'This action is unauthorized.'
                ],  401);
            }

        }
        if ($e instanceof RequestException) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message', [
                    'status' => 'error',
                    'body'   => 'Something went wrong. Please try again',
                ]);
        }

        if ($e instanceof TokenMismatchException) {
            return redirect()->back();
        }

        return parent::render($request, $e);
    }
}
