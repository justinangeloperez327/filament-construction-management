<?php

namespace App\Support;

final class StoragePath
{
    public static function project(int|string $projectReference): string
    {
        return self::join(
            config('construction.documents.root', 'documents'),
            'projects',
            self::segment($projectReference),
        );
    }

    public static function documents(int|string $projectReference, string $category = 'general'): string
    {
        return self::join(
            self::project($projectReference),
            self::segment($category),
        );
    }

    private static function join(string ...$segments): string
    {
        return implode('/', array_map(
            fn (string $segment): string => trim($segment, '/'),
            $segments,
        ));
    }

    private static function segment(int|string $value): string
    {
        $segment = preg_replace('/[^A-Za-z0-9_-]+/', '-', trim((string) $value));
        $segment = trim((string) $segment, '-');

        return $segment !== '' ? $segment : 'unknown';
    }
}
