<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'telephone',
        'email',
        'subject',
        'message',
        'user_id',
        'read_at',
        'archived_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): bool
    {
        if ($this->read_at) {
            return true;
        }

        return $this->forceFill(['read_at' => now()])->save();
    }

    public function archive(): bool
    {
        return $this->forceFill(['archived_at' => now()])->save();
    }

    public function restoreFromArchive(): bool
    {
        return $this->forceFill(['archived_at' => null])->save();
    }
}
