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
        Schema::create('inventoryqr', function (Blueprint $table) {
            $table->id();
            $table->string('item_number');
            $table->string('assets_number');
            $table->string('category');
            $table->string('brand');
            $table->string('type');
            $table->string('item_name');
            $table->string('serial_number');
            $table->date('incoming_date');
            $table->string('location');
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
        Schema::dropIfExists('inventoryqr');
    }
};
