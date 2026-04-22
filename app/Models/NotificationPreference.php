<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved',
        'revised',
        'rejected',
        'feedback',
        'new_journal',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'revised' => 'boolean',
        'rejected' => 'boolean',
        'feedback' => 'boolean',
        'new_journal' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
