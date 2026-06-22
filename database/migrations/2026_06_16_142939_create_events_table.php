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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->longText('description_en')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('location_en')->nullable();
            $table->string('category')->nullable();
            $table->string('category_en')->nullable();
            $table->foreignId('cover_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->json('gallery')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft');
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
        Schema::dropIfExists('events');
    }
};
