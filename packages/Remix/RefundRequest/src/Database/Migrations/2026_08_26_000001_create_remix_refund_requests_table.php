<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remix_refund_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();

            $table->unsignedInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->nullOnDelete();

            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();

            // pending -> processing -> refunded/failed, atau pending -> rejected
            $table->string('status')->default('pending');

            $table->text('reason')->nullable(); // deprecated, dipertahankan utk backward-compat
            $table->text('admin_note')->nullable();

            $table->decimal('requested_amount', 12, 4);
            $table->decimal('approved_amount', 12, 4)->nullable();

            $table->unsignedInteger('refund_id')->nullable();
            $table->foreign('refund_id')->references('id')->on('refunds')->nullOnDelete();

            $table->string('midtrans_refund_id')->nullable();
            $table->string('midtrans_status')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remix_refund_requests');
    }
};
