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
        'hall_id',
        'applicant_name',
        'applicant_type',
        'applicant_email',
        'requested_hall_type',
        'programme',
        'start_time',
        'end_time',
        'participants',
        'event_duration',
        'paid_status',
        'approval_status_ao',
        'approval_status_aga',
        'approval_status_ga',
        'final_approval',
        'ao_user',
        'aga_user',
        'ga_user',
        'is_emergency_booking',
        'filled_by_nic',
        'filled_by_phone',
        'reason_of_rejection',
        'date_created',
        'date_modified',
    ];

    /**
     * Get the hall associated with the booking.
     */
    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id', 'hall_id');
    }
}
