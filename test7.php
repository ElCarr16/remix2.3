<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$schema = app(\Craftile\Laravel\BlockSchemaRegistry::class)->get('@visual-debut/category-list');
$preset = new \BagistoPlus\VisualDebut\Presets\CategoryGrid();
$data = $preset->toArray();
$blockData = \Craftile\Core\Data\BlockData::hydrate($data, $schema);

print_r($blockData->settings->padding);
