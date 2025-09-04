<?php

namespace Tests\Unit\Enums;

use App\Enums\UserRole;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    /**
     * Test that UserRole enum has correct cases
     */
    public function test_user_role_has_correct_cases(): void
    {
        $this->assertEquals('admin', UserRole::ADMIN->value);
        $this->assertEquals('applicant', UserRole::APPLICANT->value);
    }

    /**
     * Test label method returns correct labels
     */
    public function test_label_returns_correct_labels(): void
    {
        $this->assertEquals('Administrador', UserRole::ADMIN->label());
        $this->assertEquals('Candidato', UserRole::APPLICANT->label());
    }

    /**
     * Test isAdmin method returns true for admin role
     */
    public function test_is_admin_returns_true_for_admin(): void
    {
        $this->assertTrue(UserRole::ADMIN->isAdmin());
        $this->assertFalse(UserRole::APPLICANT->isAdmin());
    }

    /**
     * Test isApplicant method returns true for applicant role
     */
    public function test_is_applicant_returns_true_for_applicant(): void
    {
        $this->assertTrue(UserRole::APPLICANT->isApplicant());
        $this->assertFalse(UserRole::ADMIN->isApplicant());
    }

    /**
     * Test all enum cases exist
     */
    public function test_all_enum_cases_exist(): void
    {
        $cases = UserRole::cases();

        $this->assertCount(2, $cases);
        $this->assertContains(UserRole::ADMIN, $cases);
        $this->assertContains(UserRole::APPLICANT, $cases);
    }

    /**
     * Test enum values can be created from string
     */
    public function test_enum_can_be_created_from_string(): void
    {
        $admin = UserRole::from('admin');
        $applicant = UserRole::from('applicant');

        $this->assertEquals(UserRole::ADMIN, $admin);
        $this->assertEquals(UserRole::APPLICANT, $applicant);
    }

    /**
     * Test tryFrom method with invalid value
     */
    public function test_try_from_returns_null_for_invalid_value(): void
    {
        $result = UserRole::tryFrom('invalid');

        $this->assertNull($result);
    }
}
