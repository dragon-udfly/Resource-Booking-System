<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $table = 'user_permissions';
    protected $primaryKey = 'permission_id';



    protected $fillable = [
        'user_id',
        'view_officers',
        'view_officer_details',
        'view_halls',
        'view_hall_details',
        'view_quarters',
        'view_quarter_details',
        'view_audit_log',
        'administrative_officer_approval',
        'additional_government_agent_approval',
        'government_agent_approval',
        'form_history',
        'account_setting',
        'requester',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
