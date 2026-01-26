<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledQuarterApplication extends Model
{
    protected $table = 'scheduled_quarter_application';
    protected $primaryKey = 'sq_application_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sq_application_id',
        'application_id',
        'sq_transfered_officer_priority_request',
        'sq_night_duty_priority_request',
        'sq_other_special_reason_priority_request',
        'sq_property_ownership_details',
    ];

    public $timestamps = false;

    public function quarterApplication()
    {
        return $this->belongsTo(QuarterApplication::class, 'application_id');
    }
}
