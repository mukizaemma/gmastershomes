<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hosting_rates', function (Blueprint $table) {
            $table->json('skipped_periods')->nullable()->after('usd_to_rwf');
        });
    }

    public function down(): void
    {
        Schema::table('hosting_rates', function (Blueprint $table) {
            $table->dropColumn('skipped_periods');
        });
    }
};
