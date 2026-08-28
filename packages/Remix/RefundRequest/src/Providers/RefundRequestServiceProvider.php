<?php

namespace Remix\RefundRequest\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Webkul\Sales\Models\Order;
use Remix\RefundRequest\Models\{RefundRequest, OrderDelivery};
use Remix\RefundRequest\Http\Livewire\RefundRequestWizard;
use Remix\RefundRequest\Console\Commands\AutoConfirmDeliveredOrders;

class RefundRequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/acl.php', 'acl');
        $this->mergeConfigFrom(__DIR__ . '/../Config/menu.php', 'menu.admin');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'remix');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/customer-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        // Relasi tambahan ke Order tanpa edit core
        Order::resolveRelationUsing('remixDelivery', fn ($order) => $order->hasOne(OrderDelivery::class, 'order_id'));
        Order::resolveRelationUsing('remixRefundRequests', fn ($order) => $order->hasMany(RefundRequest::class, 'order_id'));

        // Livewire component
        Livewire::component('remix-refund-request-wizard', RefundRequestWizard::class);

        // Scheduled command
        if ($this->app->runningInConsole()) {
            $this->commands([AutoConfirmDeliveredOrders::class]);
        }
    }
}
