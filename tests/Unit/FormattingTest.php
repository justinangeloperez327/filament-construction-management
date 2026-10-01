<?php

namespace Tests\Unit;

use App\Support\Formatting;
use App\Support\StoragePath;
use Tests\TestCase;

class FormattingTest extends TestCase
{
    public function test_common_business_values_are_formatted_consistently(): void
    {
        $this->assertSame('AED 1,234.50', Formatting::currency(1234.5));
        $this->assertSame('42.5%', Formatting::percentage(42.49));
        $this->assertSame('1.0 MB', Formatting::fileSize(1024 * 1024));
    }

    public function test_storage_paths_are_project_scoped_and_sanitized(): void
    {
        $this->assertSame(
            'documents/projects/PRJ-001/shop-drawings',
            StoragePath::documents('PRJ-001', 'shop drawings'),
        );

        $this->assertStringNotContainsString('..', StoragePath::documents('../PRJ 001', '../drawings'));
    }
}
