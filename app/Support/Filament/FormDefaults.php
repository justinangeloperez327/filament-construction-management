<?php

namespace App\Support\Filament;

final class FormDefaults
{
    public static function documentDisk(): string
    {
        return config('construction.documents.disk', 'local');
    }

    public static function maxUploadSize(): int
    {
        return (int) config('construction.documents.max_upload_kb', 20480);
    }

    public static function allowedDocumentExtensions(): array
    {
        return config('construction.documents.allowed_extensions', []);
    }
}
