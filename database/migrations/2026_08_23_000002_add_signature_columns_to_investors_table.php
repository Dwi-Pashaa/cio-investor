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
            $table->string('party_1_name')->nullable()->after('last_dividend_at');
            $table->string('party_2_name')->default('Cio Network')->after('party_1_name');
            $table->string('witness_1_name')->nullable()->after('party_2_name');
            $table->string('witness_2_name')->nullable()->after('witness_1_name');
            $table->longText('party_1_signature')->nullable()->after('witness_2_name');
            $table->longText('party_2_signature')->nullable()->after('party_1_signature');
            $table->longText('witness_1_signature')->nullable()->after('party_2_signature');
            $table->longText('witness_2_signature')->nullable()->after('witness_1_signature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->dropColumn([
                'party_1_name', 'party_2_name', 'witness_1_name', 'witness_2_name',
                'party_1_signature', 'party_2_signature', 'witness_1_signature', 'witness_2_signature'
            ]);
        });
    }
};
