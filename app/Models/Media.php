<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'mediable_type', 'mediable_id',
        'file_path', 'disk', 'mime_type', 'file_size',
        'type', 'is_cover', 'sort_order',
    ];

    //
    protected $casts = [
        'is_cover' => 'boolean',
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
