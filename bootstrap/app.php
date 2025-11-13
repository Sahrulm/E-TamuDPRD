<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // alias middleware kustom kamu
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // contoh lain: menambahkan global/web/api middleware bisa pakai:
        // $middleware->append(YourGlobalMiddleware::class);
        // $middleware->web( fn($stack) => $stack->append(...));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();