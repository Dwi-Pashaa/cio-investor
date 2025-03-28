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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admins_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreignId('investors_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->double('amount');
            $table->string('payment_method');
            $table->date('transfer_date');
            $table->date('confirmation_date')->nullable();
            $table->longText('notes')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
