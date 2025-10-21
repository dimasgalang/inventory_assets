<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricsQR extends Model
{
    use HasFactory;
    public $table = "fabricsqr";

    protected $fillable = [
        'supplier',
        'contract_no',
        'delivery_note_no',
        'invoice_no',
        'style',
        'item_code',
        'item_name',
        'item_category',
        'color',
        'composition',
        'lot',
        'roll_no',
        'pl_qty_yield',
        'pl_qty_kg',
        'weight',
        'fabric_role_qr_code',
        'po_number',
        'po_fabric_width',
        'source_region',
        'customs_declaration_number',
        'doc_date',
        'container_number',
        'ETA_date',
        'customs_code_shipping',
        'customs_code_warehouse',
        'customs_quantity',
        'customs_unit',
        'remark',
        'random_qr_code',
        'qr_code',
    ];
}
