<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkingFamilyQuarter extends Model
{
    use HasFactory;

    protected $table = 'marking_family_quarter';
    protected $primaryKey = 'score_id';
    public $timestamps = false;

    protected $fillable = [
        'f_application_id',
        'f_department',
        'f_years_since_application_created',
        'f_number_of_dependant',
        'is_dependant_with_disability',
        'f_distance_of_residency',
        'f_spacial_reason',
        'total_mark',
        'date_calculated',
    ];

    public function familyQuarterApplication()
    {
        return $this->belongsTo(FamilyQuarterApplication::class, 'f_application_id', 'f_application_id');
    }
}
