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
        Schema::create('sitemap_embeddings', function (Blueprint $table) {
            $table->id();
            $table->string("url");
            $table->text("embedding");
            $table->foreignId("sitemap_id")->constrained("sitemaps");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sitemap_embeddings');
    }
};
