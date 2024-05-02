<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->vector("embedding", 1536);
            $table->foreignId("sitemap_id")->constrained("sitemaps");
            $table->timestamps();
        });

        DB::statement('CREATE INDEX my_index ON sitemap_embeddings USING hnsw (embedding vector_l2_ops)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX my_index');
        Schema::dropIfExists('sitemap_embeddings');
    }
};
