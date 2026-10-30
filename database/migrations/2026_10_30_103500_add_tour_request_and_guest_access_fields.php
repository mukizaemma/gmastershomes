<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_activities', function (Blueprint $table) {
            $table->string('duration_label')->nullable()->after('title');
            $table->text('summary')->nullable()->after('description');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('manage_token', 64)->nullable()->unique()->after('email');
            $table->string('accommodation_preference')->nullable()->after('message');
            $table->string('transport_preference')->nullable()->after('accommodation_preference');
            $table->text('destinations')->nullable()->after('transport_preference');
            $table->json('selected_tours')->nullable()->after('destinations');
            $table->text('cancellation_reason')->nullable()->after('selected_tours');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropUnique(['manage_token']);
            $table->dropColumn([
                'manage_token',
                'accommodation_preference',
                'transport_preference',
                'destinations',
                'selected_tours',
                'cancellation_reason',
            ]);
        });

        Schema::table('tour_activities', function (Blueprint $table) {
            $table->dropColumn(['duration_label', 'summary']);
        });
    }
};
