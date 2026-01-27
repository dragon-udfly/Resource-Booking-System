<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyQuarterApplication extends Model
{
    protected $table = 'family_quarter_application';
    protected $primaryKey = 'f_application_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'f_application_id',
        'application_id',
        'f_dob',
        'f_date_of_last_salary_increment',
        'f_marital_status',
        'f_is_spouse_employed',
        'f_spouse_designation',
        'f_spouse_department_office',
        'f_spouse_monthly_salary',
        'f_spouse_last_increment_date',
        'f_children_details_description',
        'f_property_ownership_details',
        'f_previous_government_quarter_duration',
        'f_transformed_officer',
    ];

    public $timestamps = false;

    public function quarterApplication()
    {
        return $this->belongsTo(QuarterApplication::class, 'application_id');
    }
}
