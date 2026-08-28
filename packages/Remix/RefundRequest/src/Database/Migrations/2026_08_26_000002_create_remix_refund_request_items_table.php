<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remix_refund_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refund_request_id')->constrained('remix_refund_requests')->cascadeOnDelete();
            $table->unsignedInteger('order_item_id');
            $table->foreign('order_item_id')->references('id')->on('order_items')->cascadeOnDelete();
            $table->unsignedInteger('qty');
            $table->decimal('amount', 12, 4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remix_refund_request_items');
    }
};
