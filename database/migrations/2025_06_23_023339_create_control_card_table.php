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
        Schema::create('control_card', function (Blueprint $table) {
            $table->id();
            $table->string('assets_number');
            $table->string('control_category');
            $table->date('control_date');
            $table->integer('control_price');
            $table->string('supplier_code');
            $table->string('supplier_name');
            $table->string('void');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_card');
    }
};
