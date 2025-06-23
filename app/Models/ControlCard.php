<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlCard extends Model
{
    use HasFactory;
    public $table = "control_card";
    protected $fillable = [
        'assets_number',
        'control_category',
        'control_date',
        'control_price',
        'supplier_code',
        'supplier_name',
        'void',
    ];
}
