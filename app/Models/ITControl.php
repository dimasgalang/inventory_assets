<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ITControl extends Model
{
    use HasFactory;
    public $table = "itcontrol";
    protected $fillable = [
        'user_id',
        'assets_number',
        'device_name',
        'windows_license',
        'windows_password',
        'office_license',
        'office_email',
        'office_password',
        'void',
    ];
}
