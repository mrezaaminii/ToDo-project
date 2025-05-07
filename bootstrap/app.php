<?php

use App\Libraries\General;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            return match (true) {
                $e instanceof AuthenticationException =>
                    General::response(false, null, 'Not Authenticated', true, 401),

                $e instanceof ValidationException =>
                    General::response(false, null, $e->getMessage(), true, 422),

                $e instanceof NotFoundHttpException =>
                    General::response(false, null, 'Route not found', true, 404),

                default =>
                    General::response(false, null, 'Internal Server Error', true, 503),
            };
        });
    })
    ->create();
