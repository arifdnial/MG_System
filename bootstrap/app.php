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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            $user = $request->user();
            if ($user) {
                return match($user->role) {
                    'admin' => '/admin/dashboard',
                    'teacher' => '/teacher/dashboard',
                    'student' => '/student/dashboard',
                    default => '/login',
                };
            }
            return '/login';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
