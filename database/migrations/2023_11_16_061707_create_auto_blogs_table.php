<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auto_blogs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('quantity');
            $table->integer('interval');
            $table->boolean('status');
            $table->foreignId('preset_id')->constrained();
            $table->foreignId('integration_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_blogs');
    }
};
