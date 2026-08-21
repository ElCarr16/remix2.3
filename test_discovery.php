<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

themes()->set('visual-debut');

$manifest = app(\Craftile\Laravel\DiscoveryManifest::class);
$result = $manifest->build();
print_r($result);
