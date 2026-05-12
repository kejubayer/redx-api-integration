<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redx_webhook_requests', function (Blueprint $table): void {
            $table->id();
            $table->string('tracking_number')->nullable()->index();
            $table->timestamp('redx_timestamp')->nullable()->index();
            $table->string('status')->nullable()->index();
            $table->string('message_en')->nullable();
            $table->string('message_bn')->nullable();
            $table->string('invoice_number')->nullable()->index();
            $table->string('delivery_type')->nullable()->index();
            $table->json('payload');
            $table->json('headers')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('signature')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redx_webhook_requests');
    }
};
