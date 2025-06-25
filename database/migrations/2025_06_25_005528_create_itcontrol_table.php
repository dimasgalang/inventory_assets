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
        Schema::create('itcontrol', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('assets_number');
            $table->string('device_name');
            $table->string('windows_license');
            $table->string('windows_password');
            $table->string('office_license');
            $table->string('office_email');
            $table->string('office_password');
            $table->string('void');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itcontrol');
    }
};
