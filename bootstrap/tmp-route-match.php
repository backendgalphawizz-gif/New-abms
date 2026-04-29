<?php

declare(strict_types=1);

use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$uri = '/super-admin/subdomains/00000000-0000-0000-0000-000000000000/dashboard';
$r = Request::create($uri, 'GET', [], [], [], ['HTTP_HOST' => 'localhost', 'SERVER_NAME' => 'localhost']);
try {
    $route = $app['router']->getRoutes()->match($r);
    echo 'MATCH: '.$route->uri().' name='.$route->getName().PHP_EOL;
} catch (Throwable $e) {
    echo 'FAIL: '.get_class($e).' '.$e->getMessage().PHP_EOL;
}
