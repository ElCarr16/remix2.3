<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('remix_refund_requests', function (Blueprint $table) {
            $table->string('name')->nullable()->after('customer_id');
            $table->string('phone')->nullable()->after('name');
            $table->text('address')->nullable()->after('phone');
            $table->foreignId('reason_id')->nullable()->after('reason')
                ->constrained('remix_refund_reasons')->nullOnDelete();
            $table->string('other_reason_text')->nullable()->after('reason_id');
            $table->text('description')->nullable()->after('other_reason_text');
            $table->timestamp('agreement_accepted_at')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('remix_refund_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reason_id');
            $table->dropColumn(['name', 'phone', 'address', 'other_reason_text', 'description', 'agreement_accepted_at']);
        });
    }
};
