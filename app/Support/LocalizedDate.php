<?php

namespace App\Support;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Carbon;

class LocalizedDate
{
    public static function date(DateTimeInterface|string|null $date): string
    {
        return self::format($date, false);
    }

    public static function dateTime(DateTimeInterface|string|null $date): string
    {
        return self::format($date, true);
    }

    public static function year(DateTimeInterface|string|null $date): string
    {
        if (! $date) {
            return __('common.none');
        }

        $carbon = $date instanceof CarbonInterface
            ? $date->copy()
            : Carbon::parse($date);

        [$year] = self::toJalali(
            (int) $carbon->format('Y'),
            (int) $carbon->format('n'),
            (int) $carbon->format('j')
        );

        return self::localizeDigits((string) $year);
    }

    public static function format(DateTimeInterface|string|null $date, bool $withTime = false): string
    {
        if (! $date) {
            return __('common.none');
        }

        $carbon = $date instanceof CarbonInterface
            ? $date->copy()
            : Carbon::parse($date);

        [$year, $month, $day] = self::toJalali(
            (int) $carbon->format('Y'),
            (int) $carbon->format('n'),
            (int) $carbon->format('j')
        );

        $months = __('common.shamsi.months');
        $monthName = $months[$month - 1] ?? $month;
        $formatted = "{$day} {$monthName} {$year}";

        if ($withTime) {
            $formatted .= '، '.$carbon->format('H:i');
        }

        return self::localizeDigits($formatted);
    }

    private static function localizeDigits(string $value): string
    {
        $locale = app()->getLocale();

        if ($locale === 'en') {
            return $value;
        }

        $digits = $locale === 'ps'
            ? ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹']
            : ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        return str_replace(range(0, 9), $digits, $value);
    }

    /**
     * Converts Gregorian date parts to Jalali date parts.
     *
     * @return array{0:int,1:int,2:int}
     */
    private static function toJalali(int $gregorianYear, int $gregorianMonth, int $gregorianDay): array
    {
        $gregorianMonthDays = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];

        $gy = $gregorianYear - 1600;
        $gm = $gregorianMonth - 1;
        $gd = $gregorianDay - 1;

        $gregorianDayNumber = 365 * $gy
            + intdiv($gy + 3, 4)
            - intdiv($gy + 99, 100)
            + intdiv($gy + 399, 400);

        $gregorianDayNumber += $gregorianMonthDays[$gm] + $gd;

        if ($gm > 1 && (($gy % 4 === 0 && $gy % 100 !== 0) || ($gy % 400 === 0))) {
            $gregorianDayNumber++;
        }

        $jalaliDayNumber = $gregorianDayNumber - 79;
        $jalaliCycles = intdiv($jalaliDayNumber, 12053);
        $jalaliDayNumber %= 12053;

        $jy = 979 + (33 * $jalaliCycles) + (4 * intdiv($jalaliDayNumber, 1461));
        $jalaliDayNumber %= 1461;

        if ($jalaliDayNumber >= 366) {
            $jy += intdiv($jalaliDayNumber - 1, 365);
            $jalaliDayNumber = ($jalaliDayNumber - 1) % 365;
        }

        $jm = $jalaliDayNumber < 186
            ? 1 + intdiv($jalaliDayNumber, 31)
            : 7 + intdiv($jalaliDayNumber - 186, 30);

        $jd = 1 + ($jalaliDayNumber < 186
            ? $jalaliDayNumber % 31
            : ($jalaliDayNumber - 186) % 30);

        return [$jy, $jm, $jd];
    }
}
