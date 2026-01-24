<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quarter extends Model
{
    use HasFactory;

    protected $table = 'quarters';
    protected $primaryKey = 'quarter_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'quarter_id',
        'old_quarter_no',
        'new_quarter_no',
        'quarter_type',
        'location',
        'status',
        'date_created',
        'date_modified',
    ];
    public $timestamps = false; // Using custom date_created and date_modified
}