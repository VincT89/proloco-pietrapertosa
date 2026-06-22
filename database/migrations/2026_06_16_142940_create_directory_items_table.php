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
        Schema::create('directory_items', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_en')->nullable();
            $table->text('contact_info')->nullable();
            $table->text('contact_info_en')->nullable();
            $table->json('gallery')->nullable();
            $table->json('stats')->nullable();
            $table->integer('sort_order')->default(0);
            $table->enum('translation_status', ['draft', 'missing', 'reviewed'])->default('missing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directory_items');
    }
};
