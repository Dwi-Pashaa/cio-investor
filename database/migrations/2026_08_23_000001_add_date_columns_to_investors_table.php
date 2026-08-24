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
        Schema::table('investors', function (Blueprint $table) {
            $table->date('first_money_received_at')->nullable()->after('monthly_income');
            $table->date('first_dividend_at')->nullable()->after('first_money_received_at');
            $table->date('last_dividend_at')->nullable()->after('first_dividend_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->dropColumn(['first_money_received_at', 'first_dividend_at', 'last_dividend_at']);
        });
    }
};
