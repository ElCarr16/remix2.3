<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remix_order_deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->unique();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->timestamp('delivered_at')->nullable();        // diisi admin saat tandai "terkirim"
            $table->timestamp('confirm_deadline_at')->nullable();  // delivered_at + 5 hari
            $table->timestamp('confirmed_at')->nullable();         // customer klik "Terima" ATAU auto-confirm
            $table->string('confirmed_by')->nullable();            // 'customer' | 'system'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remix_order_deliveries');
    }
};
