<?php

namespace App\Enums;

enum PlaceStatus: string
{
    case Open = 'open';
    case Closed = 'closed';
    case TemporarilyClosed = 'temporarily_closed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Closed => 'Closed',
            self::TemporarilyClosed => 'Temporarily Closed',
        };
    }
}
