<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkingScheme extends Model
{
    use HasFactory;

    protected $table = 'marking_scheme';

    protected $fillable = [
        'marking_title',
        'marking_option',
        'defined_mark',
        'date_modified',
    ];

    public $timestamps = false;
}
