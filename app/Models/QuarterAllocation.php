<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuarterAllocation extends Model
{
    use HasFactory;

    protected $table = 'quarter_allocation';
    protected $primaryKey = 'allocation_id';

    protected $fillable = [
        'application_id',
        'quarter_id',
        'is_aga_verified',
        'aga_note',
        'is_ao_verified',
        'ao_note',
        'allocation_status',
        'allocation_date',
        'vacate_date',
    ];

    public function quarterApplication()
    {
        return $this->belongsTo(QuarterApplication::class, 'application_id', 'application_id');
    }

    public function quarter()
    {
        return $this->belongsTo(Quarter::class, 'quarter_id', 'quarter_id');
    }
}
