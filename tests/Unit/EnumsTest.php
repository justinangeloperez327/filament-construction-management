<?php

namespace Tests\Unit;

use App\Enums\ApprovalStatus;
use App\Enums\DocumentStatus;
use App\Enums\Priority;
use App\Enums\ProjectStatus;
use PHPUnit\Framework\TestCase;

class EnumsTest extends TestCase
{
    public function test_status_enums_expose_labels_and_colors(): void
    {
        $this->assertSame('Active', ProjectStatus::Active->label());
        $this->assertSame('success', ProjectStatus::Active->color());
        $this->assertSame('Critical', Priority::Critical->label());
        $this->assertSame('danger', Priority::Critical->color());
        $this->assertSame('Approved', ApprovalStatus::Approved->label());
        $this->assertSame('Approved With Comments', DocumentStatus::ApprovedWithComments->label());
    }

    public function test_enums_expose_select_options(): void
    {
        $this->assertSame('High', Priority::options()['high']);
        $this->assertSame('On Hold', ProjectStatus::options()['on_hold']);
    }
}
