<?php
$lines = [];
$lines[] = '<?php';
$lines[] = '';
$lines[] = 'use Illuminate\Foundation\Application;';
$lines[] = 'use Illuminate\Foundation\Configuration\Exceptions;';
$lines[] = 'use Illuminate\Foundation\Configuration\Middleware;';
$lines[] = '';
$lines[] = "return Application::configure(basePath: dirname(__DIR__))";
$lines[] = "    ->withRouting(";
$lines[] = "        web: __DIR__.'/../routes/web.php',";
$lines[] = "        commands: __DIR__.'/../routes/console.php',";
$lines[] = "        health: '/up',";
$lines[] = "    )";
$lines[] = "    ->withMiddleware(function (Middleware \$middleware) {";
$lines[] = '        $middleware->alias([';
$lines[] = "            'role' => \\App\\Http\\Middleware\\RoleMiddleware::class,";
$lines[] = '        ]);';
$lines[] = "        \$middleware->redirectGuestsTo('/login');";
$lines[] = "        \$middleware->redirectUsersTo(function (\\Illuminate\\Http\\Request \$request) {";
$lines[] = '            $user = $request->user();';
$lines[] = '            if ($user) {';
$lines[] = '                return match($user->role) {';
$lines[] = "                    'admin' => '/admin/dashboard',";
$lines[] = "                    'teacher' => '/teacher/dashboard',";
$lines[] = "                    'student' => '/student/dashboard',";
$lines[] = "                    default => '/login',";
$lines[] = '                };';
$lines[] = '            }';
$lines[] = "            return '/login';";
$lines[] = '        });';
$lines[] = '    })';
$lines[] = '    ->withExceptions(function (Exceptions $exceptions) {';
$lines[] = '        //';
$lines[] = '    })->create();';
$lines[] = '';

$path = __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'app.php';
$content = implode("\n", $lines);

// Delete old file first
if (file_exists($path)) {
    unlink($path);
}

// Write new content
$bytes = file_put_contents($path, $content);
echo "Written {$bytes} bytes to {$path}\n";
echo "Verifying...\n";
echo file_get_contents($path);
