<?php

namespace App\Support;

use Carbon\Carbon;
use DateTimeInterface;

final class Formatting
{
    public static function date(DateTimeInterface|string|null $value, ?string $format = null): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $date = $value instanceof DateTimeInterface
            ? Carbon::instance($value)
            : Carbon::parse($value);

        return $date
            ->timezone(config('app.timezone'))
            ->format($format ?? config('construction.formats.date'));
    }

    public static function dateTime(DateTimeInterface|string|null $value, ?string $format = null): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $date = $value instanceof DateTimeInterface
            ? Carbon::instance($value)
            : Carbon::parse($value);

        return $date
            ->timezone(config('app.timezone'))
            ->format($format ?? config('construction.formats.date_time'));
    }

    public static function currency(int|float|string|null $value, ?string $currency = null, ?int $decimals = null): string
    {
        $amount = is_numeric($value) ? (float) $value : 0.0;
        $currency ??= config('construction.currency');
        $decimals ??= (int) config('construction.formats.currency_decimals', 2);

        return sprintf('%s %s', $currency, number_format($amount, $decimals));
    }

    public static function percentage(int|float|string|null $value, ?int $decimals = null): string
    {
        $percentage = is_numeric($value) ? (float) $value : 0.0;
        $decimals ??= (int) config('construction.formats.percentage_decimals', 1);

        return number_format($percentage, $decimals).'%';
    }

    public static function fileSize(int $bytes, int $decimals = 1): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = min((int) floor(log($bytes, 1024)), count($units) - 1);
        $value = $bytes / (1024 ** $power);

        return number_format($value, $decimals).' '.$units[$power];
    }
}
