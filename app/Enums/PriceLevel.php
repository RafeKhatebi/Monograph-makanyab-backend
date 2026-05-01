<?php

namespace App\Enums;

enum PriceLevel: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Luxury = 'luxury';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
