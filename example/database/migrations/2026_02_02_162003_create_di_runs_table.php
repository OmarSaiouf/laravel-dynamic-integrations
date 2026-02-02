<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Omarsaiouf\Integrations\Enums\RunStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('di_runs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('provider_key');
            $table->string('endpoint_key');
            $table->enum('status', RunStatus::getAllValue());
            $table->string('http_status')->nullable();
            $table->string('duration_ms')->nullable();
            $table->json('request_snapshot')->nullable();
            $table->json('response_snapshot')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('di_runs');
    }
};
