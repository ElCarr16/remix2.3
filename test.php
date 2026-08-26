<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = Webkul\Sales\Models\Order::latest()->first();
dump('ID', $order->id);
dump('status', $order->status);
dump('fulfillment', $order->fulfillment_status);
dump('shipments', $order->shipments->count());
dump('invoices', $order->invoices->count());
