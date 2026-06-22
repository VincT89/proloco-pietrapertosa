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
        Schema::create('page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug')->unique();
            $table->string('hero_title')->nullable();
            $table->string('hero_title_en')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_subtitle_en')->nullable();
            $table->string('hero_image_url')->nullable();
            $table->string('intro_title')->nullable();
            $table->string('intro_title_en')->nullable();
            $table->text('intro_text')->nullable();
            $table->text('intro_text_en')->nullable();
            $table->enum('translation_status', ['draft', 'missing', 'reviewed'])->default('missing');
            $table->string('seo_title')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_description_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_settings');
    }
};
