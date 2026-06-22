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
        Schema::table('media', function (Blueprint $table) {
            $table->string('type')->default('image')->after('id'); // image | video
            $table->string('provider')->default('cloudinary')->after('type'); // cloudinary | facebook | instagram
            $table->string('source_url')->nullable()->after('url');
            $table->string('embed_url')->nullable()->after('source_url');
            $table->string('thumbnail_url')->nullable()->after('embed_url');
            $table->string('duration')->nullable()->after('thumbnail_url');
            $table->json('metadata')->nullable()->after('duration');
            
            // public_id e url in origine erano string required. public_id deve diventare nullable per facebook/instagram
            $table->string('public_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'provider',
                'source_url',
                'embed_url',
                'thumbnail_url',
                'duration',
                'metadata'
            ]);
            $table->string('public_id')->nullable(false)->change();
        });
    }
};
