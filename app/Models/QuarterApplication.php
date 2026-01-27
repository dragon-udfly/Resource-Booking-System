<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuarterApplication extends Model
{
    protected $table = 'quarter_application';
    protected $primaryKey = 'application_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'application_id',
        'quarter_type',
        'officer_name',
        'gender',
        'nic',
        'designation',
        'service_grade',
        'permanent_address',
        'temporary_address',
        'monthly_salary',
        'phone_number',
        'email',
        'date_of_assumption_of_duties',
        'date_created',
        'date_modified',
    ];

    public $timestamps = false;

    /**
     * Get the quarter allocation record associated with the application.
     */
    public function quarterAllocation()
    {
        return $this->hasOne(QuarterAllocation::class, 'application_id', 'application_id');
    }

    /**
     * Get the family quarter application record associated with the application.
     */
    public function familyQuarterApplication()
    {
        return $this->hasOne(FamilyQuarterApplication::class, 'application_id', 'application_id');
    }
}
