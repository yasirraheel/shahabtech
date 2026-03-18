<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('frontends')) {
            return;
        }

        if (!Schema::hasColumn('frontends', 'status')) {
            Schema::table('frontends', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->after('data_values');
            });
        }

        DB::table('frontends')
            ->where('data_keys', 'brand.element')
            ->whereNull('status')
            ->update(['status' => 1]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('frontends')) {
            return;
        }

        if (Schema::hasColumn('frontends', 'status')) {
            Schema::table('frontends', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
