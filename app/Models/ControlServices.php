<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlServices extends Model
{
    use HasFactory;
    public $table = "control_services";
    protected $fillable = [
        'control_id',
        'control_name',
        'void',
    ];
}
