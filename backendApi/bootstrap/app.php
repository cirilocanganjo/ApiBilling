<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'is_authenticated' => \App\Http\Middleware\VerifyIfUserIsAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    // Exemplo: Para qualquer exceção HTTP, usar uma view customizada
  $exceptions->render(function (Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
    $status = $e->getStatusCode();

    if (view()->exists("errors.{$status}")) {
        return response()->view("errors.{$status}", [
            'exception' => $e,
            'status' => $status
        ], $status);
    }

        return response()->view('errors.default', [
            'exception' => $e,
            'status' => $status
        ], $status);
    });


    // Ignorar report de certas exceções (ex: não logar 404s)
    $exceptions->dontReportDuplicates();
    $exceptions->dontReport([
        \Illuminate\Http\Exceptions\ThrottleRequestsException::class,
    ]);

    })->create();
