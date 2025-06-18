<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    public $table = "document";
    protected $fillable = [
        'uploader_id',
        'document_name',
        'original_name',
        'void'
    ];
}
