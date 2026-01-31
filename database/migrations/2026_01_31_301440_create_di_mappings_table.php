<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Omarsaiouf\Integrations\Enums\MappingMode;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('di_mappings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('endpoints_id')->constrained()->cascadeOnDelete();
            $table->enum('type', MappingMode::getAllValue());
            $table->json('rules');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('di_mappings');
    }
};
