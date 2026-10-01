<?php

namespace App\Support\Filament;

final class TableDefaults
{
    public static function paginationOptions(): array
    {
        return config('construction.tables.pagination_options', [10, 25, 50, 100]);
    }

    public static function defaultPagination(): int
    {
        return (int) config('construction.tables.default_pagination', 25);
    }
}
