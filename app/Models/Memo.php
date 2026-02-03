<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Memo extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'body',
        'status',
        'is_read',
    ];

    /**
     * Get the sender of the memo.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    /**
     * Get the receiver of the memo.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'user_id');
    }

    /**
     * Encrypt the subject when setting.
     */
    public function setSubjectAttribute($value)
    {
        $this->attributes['subject'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt the subject when getting.
     */
    public function getSubjectAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // Fallback if not encrypted or valid
        }
    }

    /**
     * Encrypt the body when setting.
     */
    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt the body when getting.
     */
    public function getBodyAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }
}
