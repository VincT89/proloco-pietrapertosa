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
        Schema::table('page_settings', function (Blueprint $table) {
            $table->string('hero_cta_text')->nullable()->after('intro_text_en');
            $table->string('hero_cta_text_en')->nullable()->after('hero_cta_text');
            $table->string('hero_cta_url')->nullable()->after('hero_cta_text_en');
            $table->decimal('hero_overlay_opacity', 3, 2)->default(0.4)->after('hero_media_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_settings', function (Blueprint $table) {
            $table->dropColumn(['hero_cta_text', 'hero_cta_text_en', 'hero_cta_url', 'hero_overlay_opacity']);
        });
    }
};
