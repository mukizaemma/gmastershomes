<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_rates', function (Blueprint $table) {
            $table->id();
            $table->decimal('domain_amount', 10, 2)->default(0);
            $table->decimal('ssl_amount', 10, 2)->default(0);
            $table->decimal('support_amount', 10, 2)->default(0);
            $table->decimal('usd_to_rwf', 12, 2)->default(1530);
            $table->timestamps();
        });

        DB::table('hosting_rates')->insert([
            'domain_amount' => 0,
            'ssl_amount' => 0,
            'support_amount' => 0,
            'usd_to_rwf' => 1530,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::create('hosting_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->date('period_start')->unique();
            $table->date('period_end');
            $table->date('issued_on');
            $table->string('status', 20);
            $table->decimal('total', 10, 2);
            $table->decimal('usd_to_rwf', 12, 2)->default(1530);
            $table->json('lines');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('last_manual_reminder_at')->nullable();
            $table->timestamp('notice_30_at')->nullable();
            $table->timestamp('notice_2_at')->nullable();
            $table->timestamp('notice_due_at')->nullable();
            $table->timestamp('notice_expired_at')->nullable();
            $table->timestamps();
        });

        DB::table('hosting_invoices')->insert([
            'number' => 'IREME/gmasters/PH001/2026',
            'period_start' => '2026-10-01',
            'period_end' => '2027-09-30',
            'issued_on' => '2026-10-01',
            'status' => 'paid',
            'total' => 80,
            'usd_to_rwf' => 1530,
            'lines' => json_encode([
                ['key' => 'domain', 'label' => 'Domain registration', 'amount' => '0.00'],
                ['key' => 'hosting', 'label' => 'Hosting', 'amount' => '80.00'],
                ['key' => 'ssl', 'label' => 'SSL', 'amount' => '0.00'],
                ['key' => 'support', 'label' => 'Support', 'amount' => '0.00'],
            ]),
            'paid_at' => '2026-10-01 08:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_invoices');
        Schema::dropIfExists('hosting_rates');
    }
};
