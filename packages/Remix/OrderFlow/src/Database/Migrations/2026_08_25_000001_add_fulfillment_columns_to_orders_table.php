<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('fulfillment_status', 40)
                ->default('waiting_payment')
                ->after('status')
                ->index();

            $table->string('courier_name', 100)->nullable()->after('fulfillment_status');
            $table->string('courier_code', 30)->nullable()->after('courier_name');
            $table->string('courier_tracking_number', 100)->nullable()->after('courier_code');

            $table->timestamp('approved_at')->nullable()->after('courier_tracking_number');
            $table->timestamp('shipped_at')->nullable()->after('approved_at');
            $table->timestamp('completion_requested_at')->nullable()->after('shipped_at');
            $table->timestamp('completed_confirmed_at')->nullable()->after('completion_requested_at');

            $table->text('admin_rejection_reason')->nullable()->after('completed_confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'fulfillment_status', 'courier_name', 'courier_code', 'courier_tracking_number',
                'approved_at', 'shipped_at', 'completion_requested_at',
                'completed_confirmed_at', 'admin_rejection_reason',
            ]);
        });
    }
};
