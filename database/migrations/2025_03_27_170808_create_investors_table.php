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
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreignId('types_id')->nullable()->references('id')->on('types')->nullOnDelete();
            $table->foreignId('categories_id')->nullable()->references('id')->on('categoris')->nullOnDelete();
            $table->double('bussines_funds');
            $table->string('persentase');
            $table->double('monthly_income');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
