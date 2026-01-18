<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    protected $table = 'hall';
    protected $primaryKey = 'hall_id';
    public $incrementing = false; // hall_id is not auto-incrementing
    protected $keyType = 'string'; // hall_id is a string

    public $timestamps = false; // Using custom date_created and date_modified

    protected $fillable = [
        'hall_id',
        'hall_type',
        'capacity',
        'description',
        'current_state',
        'special_notice',
        'booking_state',
        'date_created',
        'date_modified',
    ];
}
