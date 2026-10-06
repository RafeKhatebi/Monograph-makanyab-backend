<?php

namespace App\Support;

final class LocalizedAfghanistanLocation
{
    private static ?array $provinces = null;

    private static ?array $provinceLookup = null;

    private static ?array $districtLookup = null;

    public static function province(?string $value): ?string
    {
        if (blank($value)) {
            return $value;
        }

        self::load();
        $province = self::$provinceLookup[self::key($value)] ?? null;

        return $province ? self::label($province) : $value;
    }

    public static function district(?string $value, ?string $province = null): ?string
    {
        if (blank($value)) {
            return $value;
        }

        self::load();
        $provinceRecord = $province ? (self::$provinceLookup[self::key($province)] ?? null) : null;
        $district = $provinceRecord
            ? (self::$districtLookup[$provinceRecord['value']][self::key($value)] ?? null)
            : null;

        if (! $district) {
            foreach (self::$districtLookup as $districts) {
                $district = $districts[self::key($value)] ?? null;
                if ($district) {
                    break;
                }
            }
        }

        return $district ? self::label($district) : $value;
    }

    private static function label(array $location): string
    {
        return $location['labels'][app()->getLocale()]
            ?? $location['labels']['fa']
            ?? $location['value'];
    }

    private static function key(string $value): string
    {
        return mb_strtolower(str_replace(['ي', 'ك'], ['ی', 'ک'], trim($value)));
    }

    private static function load(): void
    {
        if (self::$provinces !== null) {
            return;
        }

        self::$provinces = json_decode(file_get_contents(resource_path('data/afghanistan-locations.json')), true)['provinces'];
        self::$provinceLookup = [];
        self::$districtLookup = [];

        foreach (self::$provinces as $province) {
            foreach (array_merge([$province['value']], array_values($province['labels'])) as $name) {
                self::$provinceLookup[self::key($name)] = $province;
            }

            self::$districtLookup[$province['value']] = [];
            foreach ($province['districts'] as $district) {
                foreach (array_merge([$district['value']], array_values($district['labels'])) as $name) {
                    self::$districtLookup[$province['value']][self::key($name)] = $district;
                }
            }
        }
    }
}
