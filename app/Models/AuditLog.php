<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_log';
    protected $primaryKey = 'audit_log_id';
    public $timestamps = false;

    protected $fillable = [
        'log_title',
        'performed_by',
        'date_performed',
        'time_performed',
    ];
}
