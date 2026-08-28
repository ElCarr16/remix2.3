<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remix_refund_request_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refund_request_id')->constrained('remix_refund_requests')->cascadeOnDelete();
            $table->string('path');
            $table->string('type'); // image | video
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remix_refund_request_media');
    }
};
