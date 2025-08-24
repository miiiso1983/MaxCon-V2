<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


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
        // Log detailed error information
        $exceptions->reportable(function (Throwable $e) {
            Log::error('Server Error 500', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'user_id' => Auth::id(),
                'tenant_id' => Auth::check() ? Auth::user()->tenant_id : null,
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
                'timestamp' => now()->toISOString(),
            ]);
        });

        // Global exception handler fallback to avoid server error page hiding messages
        $exceptions->render(function (Throwable $e) {
            // Return plain text for minimal routes or JSON for API-like checks
            $path = request()->path();
            if (str_starts_with($path, '__') || str_contains($path, 'test') || str_contains($path, 'ping')) {
                return response('EX: ' . $e->getMessage(), 500);
            }

            // For 500 errors, pass exception details to the error view
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $e->getStatusCode() === 500) {
                return response()->view('errors.500', [
                    'exception' => $e,
                    'message' => $e->getMessage()
                ], 500);
            }

            // For other server errors (non-HTTP exceptions), also pass details
            if (!($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException)) {
                return response()->view('errors.500', [
                    'exception' => $e,
                    'message' => $e->getMessage()
                ], 500);
            }

            // Otherwise, proceed with default behavior
            return null; // use default handler
        });
    })->create();
