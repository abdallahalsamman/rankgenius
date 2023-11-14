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
        Schema::create('presets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained();
            $table->string('name')->nullable();
            $table->string('mode')->nullable();
            $table->text('details')->nullable();
            $table->string('language')->nullable();
            $table->integer('creativity')->nullable();
            $table->string('tone_of_voice')->nullable();
            $table->text('custom_instructions')->nullable();
            $table->string('point_of_view')->nullable();
            $table->text('call_to_action')->nullable();
            $table->string('sitemap_url')->nullable();
            $table->string('sitemap_filter')->nullable();
            $table->boolean('automatic_external_link')->nullable();
            $table->text('extra_links')->nullable();
            $table->boolean('featured_image_enabled')->nullable();
            $table->boolean('in_article_images')->nullable();
            $table->boolean('automatic_youtube_videos')->nullable();
            $table->text('youtube_videos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presets');
    }
};
