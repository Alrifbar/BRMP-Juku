<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Journal extends Model
{
    protected $fillable = [
        'user_id',
        'no',
        'tanggal',
        'title',
        'uraian_pekerjaan',
        'dokumen_pekerjaan',
        'penilai_kasubang',
        'penilai_tu',
        'penilai_katimker',
        'jenis_katimker',
        'tags',
        'is_private',
        'received_by_admin',
        'received_at',
        'admin_checks',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_private' => 'boolean',
        'tanggal' => 'date',
        'received_by_admin' => 'boolean',
        'received_at' => 'datetime',
        'admin_checks' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'journal_admin', 'journal_id', 'admin_id')
            ->withPivot('status')
            ->withTimestamps();
    }
}
