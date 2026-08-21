<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$tpl = Illuminate\Support\Facades\DB::table('visual_theme_templates')->where('theme', 'visual-debut')->where('name', 'index')->first();
if ($tpl) {
    echo $tpl->data;
} else {
    echo 'NOT FOUND';
}
