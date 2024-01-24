<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
// use Symfony\Component\Routing\Exception\TokenExpiredException;
use Symfony\Component\Routing\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Auth\Access\AuthorizationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException)
        {
            return response()->json( [
                'success' => 0,
                'message' => 'This Method is not allowed for the requested route',
                'status' => '405',
            ], 405 );
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json( [
                'success' => 0,
                'message' => 'This Route is not found',
                'status' => '404',
            ], 404 );
        }
        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], $exception->getStatusCode());
        }



      

        if ($exception instanceof TokenExpiredException) {
            return response()->json( [
                'error' => 'Unauthenticated',
                'message' => 'Please login to be authenticated',
                'status' => '401',
            ], 401 );
        }

        if ($exception instanceof JWTException) {
            return response()->json( [
                'error' => 'Unauthenticated',
                'message' => 'Please login to be authenticated',
                'status' => '401',
            ], 401 );
        }


        if ($exception instanceof TokenInvalidException) {
            return response()->json( [
                'error' => 'Unauthenticated',
                'message' => 'Please login to be authenticated',
                'status' => '401',
            ], 401 );
        }


        return parent::render($request, $exception);

    }

    protected function unauthorized($request, AuthorizationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => $exception->getMessage(),
            ], 403);
        }

        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }


    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }



}
