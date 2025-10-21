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
        Schema::create('fabricsqr', function (Blueprint $table) {
            $table->id();
            $table->string('supplier')->nullable();
            $table->string('contract_no')->nullable();
            $table->string('delivery_note_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('style')->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->string('item_category')->nullable();
            $table->string('color')->nullable();
            $table->string('composition')->nullable();
            $table->string('lot')->nullable();
            $table->string('roll_no')->nullable();
            $table->string('pl_qty_yield')->nullable();
            $table->string('pl_qty_kg')->nullable();
            $table->string('weight')->nullable();
            $table->string('fabric_role_qr_code')->nullable();
            $table->string('po_number')->nullable();
            $table->string('po_fabric_width')->nullable();
            $table->string('source_region')->nullable();
            $table->string('customs_declaration_number')->nullable();
            $table->string('doc_date')->nullable();
            $table->string('container_number')->nullable();
            $table->string('ETA_date')->nullable();
            $table->string('customs_code_shipping')->nullable();
            $table->string('customs_code_warehouse')->nullable();
            $table->string('customs_quantity')->nullable();
            $table->string('customs_unit')->nullable();
            $table->string('remark')->nullable();
            $table->string('random_qr_code')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fabricsqr');
    }
};
