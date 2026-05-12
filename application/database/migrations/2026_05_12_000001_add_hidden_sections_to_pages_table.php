<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pages') || Schema::hasColumn('pages', 'hidden_sections')) {
            return;
        }

        Schema::table('pages', function (Blueprint $table) {
            $table->json('hidden_sections')->nullable()->after('secs');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('pages') || !Schema::hasColumn('pages', 'hidden_sections')) {
            return;
        }

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('hidden_sections');
        });
    }
};
