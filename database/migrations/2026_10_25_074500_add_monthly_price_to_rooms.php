<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (! Schema::hasColumn('rooms', 'monthly_price')) {
                $table->decimal('monthly_price', 12, 2)->nullable()->after('price_rwf');
            }
            if (! Schema::hasColumn('rooms', 'monthly_price_rwf')) {
                $table->decimal('monthly_price_rwf', 12, 2)->nullable()->after('monthly_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'monthly_price_rwf')) {
                $table->dropColumn('monthly_price_rwf');
            }
            if (Schema::hasColumn('rooms', 'monthly_price')) {
                $table->dropColumn('monthly_price');
            }
        });
    }
};
