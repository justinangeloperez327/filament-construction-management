<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ApplicationFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_admin_panel(): void
    {
        $this->get('/')->assertRedirect('/admin');
    }

    public function test_admin_login_page_is_available(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_notifications_table_is_available(): void
    {
        $this->assertTrue(Schema::hasTable('notifications'));
    }

    public function test_construction_defaults_are_configured(): void
    {
        $this->assertSame('Asia/Dubai', config('app.timezone'));
        $this->assertSame('AED', config('construction.currency'));
        $this->assertSame('local', config('construction.documents.disk'));
        $this->assertSame(25, config('construction.tables.default_pagination'));
    }
}
