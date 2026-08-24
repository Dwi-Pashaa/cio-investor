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
            $table->string('party_1_address')->nullable()->after('party_1_name');
            $table->string('party_1_bank')->nullable()->after('party_1_address');
            $table->string('party_1_account_number')->nullable()->after('party_1_bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->dropColumn(['party_1_address', 'party_1_bank', 'party_1_account_number']);
        });
    }
};
