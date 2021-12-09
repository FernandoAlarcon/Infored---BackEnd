<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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

    public function report(Throwable $exception) // <-- USE Throwable HERE
    {
        parent::report($exception);
    }
    public function render($request, Throwable $exception) // AND HERE
    {   
        if ($exception instanceof Tymon\JWTAuth\Exceptions\TokenExpiredException) {
			return response()->json(['error' => 'Token has expired'], $exception->getStatusCode());
		} elseif ($exception instanceof Tymon\JWTAuth\Exceptions\TokenInvalidException) {
			return response()->json(['error' => 'Token is invalid'], $exception->getStatusCode());
		} elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
			return response()->json(['error' => 'Unauthorized'], 401);
		} elseif ($exception instanceof WebsiteTokenMissingException) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}
        return parent::render($request, $exception);
    }

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
