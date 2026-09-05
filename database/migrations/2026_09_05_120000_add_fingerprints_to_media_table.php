<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('original_name')->nullable();
            // Existing duplicate records keep their references until explicitly merged.
            $table->char('content_hash', 64)->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex(['content_hash']);
            $table->dropColumn(['original_name', 'content_hash']);
        });
    }
};
