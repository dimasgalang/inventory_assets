<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineQR extends Model
{
    use HasFactory;
    public $table = "machineqr";
    protected $fillable = [
        'customs_code',
        'machine_code',
        'brand',
        'type',
        'machine_name',
        'serial_number',
        'qr_code',
        'void'
    ];
}
