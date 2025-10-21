<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoriesQR extends Model
{
    use HasFactory;
    public $table = "accessoriesqr";

    protected $fillable = [
        'supplier',
        'contract_no',
        'delivery_note_no',
        'invoice_no',
        'style',
        'lot',
        'item_code',
        'item_name',
        'item_category',
        'color',
        'size',
        'composition',
        'box_no',
        'qty',
        'unit',
        'fabric_role_qr_code',
        'po_number',
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
