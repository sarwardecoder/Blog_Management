<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('visibility', ['public', 'private'])->default('public')->after('status');
            $table->fullText(['title', 'content']); // Add full-text search capabilities
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('visibility');
            $table->dropFullText(['title', 'content']);
        });
    }
};