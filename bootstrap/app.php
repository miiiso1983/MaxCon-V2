<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'tenant' => \App\Http\Middleware\TenantMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'invoice.permissions' => \App\Http\Middleware\InvoicePermissions::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Global exception handler fallback to avoid server error page hiding messages
        $exceptions->render(function (Throwable $e) {
            // Return plain text for minimal routes or JSON for API-like checks
            $path = request()->path();
            if (str_starts_with($path, '__') || str_contains($path, 'test') || str_contains($path, 'ping')) {
                return response('EX: ' . $e->getMessage(), 500);
            }
            // Otherwise, proceed with default behavior
            return null; // use default handler
        });
    })->create();
