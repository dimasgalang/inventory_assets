<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ITMaintenance extends Model
{
    use HasFactory;
    public $table = "itmaintenance";
    protected $fillable = [
        'month',
        'year',
        'assets_number',
        'condition',
        'checking_date',
        'checker',
        'remark',
        'void',
    ];
}
