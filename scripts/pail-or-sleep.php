<?php

declare(strict_types=1);

// Laravel Pail requires pcntl (not available on typical Windows PHP builds).
// In dev we keep this process alive so `composer run dev` stays "full stack".

if (function_exists('pcntl_fork')) {
    passthru('php artisan pail --timeout=0', $code);
    exit($code);
}

fwrite(STDERR, "Pail disabled: pcntl extension not available.\n");

while (true) {
    sleep(60);
}

