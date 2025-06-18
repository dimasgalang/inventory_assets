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
        Schema::create('machineqr', function (Blueprint $table) {
            $table->id();
            $table->string('customs_code');
            $table->string('machine_code');
            $table->string('brand');
            $table->string('type');
            $table->string('machine_name');
            $table->string('serial_number');
            $table->string('qr_code')->nullable();
            $table->string('void');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machineqr');
    }
};
