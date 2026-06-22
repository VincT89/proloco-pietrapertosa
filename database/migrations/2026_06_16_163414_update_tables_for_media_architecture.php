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
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('gallery');
        });
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('gallery');
        });
        Schema::table('directory_items', function (Blueprint $table) {
            $table->dropColumn('gallery');
        });
        Schema::table('page_settings', function (Blueprint $table) {
            $table->dropColumn('hero_image_url');
            $table->foreignId('hero_media_id')->nullable()->constrained('media')->nullOnDelete();
        });
        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->dropColumn('media_urls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->json('gallery')->nullable();
        });
        Schema::table('news', function (Blueprint $table) {
            $table->json('gallery')->nullable();
        });
        Schema::table('directory_items', function (Blueprint $table) {
            $table->json('gallery')->nullable();
        });
        Schema::table('page_settings', function (Blueprint $table) {
            $table->dropForeign(['hero_media_id']);
            $table->dropColumn('hero_media_id');
            $table->string('hero_image_url')->nullable();
        });
        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->json('media_urls')->nullable();
        });
    }
};
