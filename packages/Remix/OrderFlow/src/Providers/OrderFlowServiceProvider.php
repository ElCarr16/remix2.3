<?php

namespace Remix\OrderFlow\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Remix\OrderFlow\Listeners\GuardShipmentCreation;
use Remix\OrderFlow\Observers\OrderObserver;
use Remix\OrderFlow\Observers\ShipmentObserver;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\Shipment;

class OrderFlowServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/order-flow.php', 'order-flow');
        $this->mergeConfigFrom(__DIR__.'/../Config/acl.php', 'acl');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/admin.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'order-flow');

        Order::observe(OrderObserver::class);
        Shipment::observe(ShipmentObserver::class);

        Event::listen('sales.shipment.create.before', GuardShipmentCreation::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Remix\OrderFlow\Console\Commands\AutoCompleteOrders::class,
            ]);
        }

        \Livewire\Livewire::component('order-tracking-timeline', \Remix\OrderFlow\Http\Livewire\OrderTrackingTimeline::class);
    }
}
