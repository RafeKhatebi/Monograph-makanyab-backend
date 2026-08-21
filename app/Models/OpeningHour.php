<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use DateTimeInterface;

class OpeningHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id', 'day_of_week', 'open_time', 'close_time', 'is_closed',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_closed' => 'boolean',
    ];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function isOpenAt(?DateTimeInterface $at = null, ?string $timezone = null): bool
    {
        if ($this->is_closed || ! $this->open_time || ! $this->close_time) {
            return false;
        }

        $timezone ??= config('app.timezone');
        $moment = Carbon::instance($at ?? now())->setTimezone($timezone);
        $open = $this->timeInMinutes($this->open_time);
        $close = $this->timeInMinutes($this->close_time);
        $current = ($moment->hour * 60) + $moment->minute;

        if ($open < $close) {
            return $moment->dayOfWeek === $this->day_of_week
                && $current >= $open
                && $current < $close;
        }

        if ($open > $close && $moment->dayOfWeek === $this->day_of_week) {
            return $current >= $open;
        }

        if ($open > $close && $current < $close) {
            $previousDay = ($moment->dayOfWeek + 6) % 7;

            return $this->place?->openingHours()
                ->where('day_of_week', $previousDay)
                ->where('is_closed', false)
                ->whereColumn('open_time', '>', 'close_time')
                ->where('close_time', '>', $moment->format('H:i:s'))
                ->exists() ?? false;
        }

        return false;
    }

    private function timeInMinutes(string $time): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', $time));

        return ($hours * 60) + $minutes;
    }
}
