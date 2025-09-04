<?php

namespace Tests\Feature\Middleware;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin middleware allows admin users
     */
    public function test_admin_middleware_allows_admin_users(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($admin)->get('/admin/forms');

        $response->assertOk();
    }

    /**
     * Test admin middleware blocks applicant users
     */
    public function test_admin_middleware_blocks_applicant_users(): void
    {
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);

        $response = $this->actingAs($applicant)->get('/admin/forms');

        $response->assertForbidden();
    }

    /**
     * Test admin middleware blocks unauthenticated users
     */
    public function test_admin_middleware_blocks_unauthenticated_users(): void
    {
        $response = $this->get('/admin/forms');

        $response->assertRedirect('/login');
    }
}

class ApplicantMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test applicant middleware allows applicant users
     */
    public function test_applicant_middleware_allows_applicant_users(): void
    {
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);

        $response = $this->actingAs($applicant)->get('/forms');

        $response->assertOk();
    }

    /**
     * Test applicant middleware blocks admin users
     */
    public function test_applicant_middleware_blocks_admin_users(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($admin)->get('/forms');

        $response->assertForbidden();
    }

    /**
     * Test applicant middleware blocks unauthenticated users
     */
    public function test_applicant_middleware_blocks_unauthenticated_users(): void
    {
        $response = $this->get('/forms');

        $response->assertRedirect('/login');
    }
}
