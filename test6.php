<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$preset = new \BagistoPlus\VisualDebut\Presets\CategoryGrid();
print_r($preset->toArray());
