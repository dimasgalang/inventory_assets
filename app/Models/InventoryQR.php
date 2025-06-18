<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryQR extends Model
{
    use HasFactory;
    public $table = "inventoryqr";
    protected $fillable = [
        'item_number',
        'assets_number',
        'category',
        'brand',
        'type',
        'item_name',
        'serial_number',
        'incoming_date',
        'location',
        'qr_code',
        'void',
    ];
}
