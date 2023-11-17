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
        Schema::create('shopify_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id');
            $table->string('shop_name');
            $table->string('access_token');
            $table->string('blog');
            $table->string('author');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopify_integrations');
    }
};
