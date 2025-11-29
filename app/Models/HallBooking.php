<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallBooking extends Model
{
    use HasFactory;

    protected $table = 'hall_booking';
    protected $primaryKey = 'booking_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // Using custom date_created and date_modified

    protected $fillable = [
        'booking_id',
        'applicant_name',
        'applicant_type',
        'requested_hall_type',
        'hall_id',
        'programme',
        'event_date',
        'participants',
        'event_duration',
        'paid_status',
        'is_emergency_booking',
        'filled_by_nic',
        'filled_by_phone',
        'administrative_officer_approved',
        'additional_government_agent_approved',
        'government_agent_approved',
        'final_approval',
        'date_created',
        'date_modified',
    ];
}
