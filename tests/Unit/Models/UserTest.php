<?php

namespace Tests\Unit\Models;

use App\Enums\UserRole;
use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user has correct fillable attributes
     */
    public function test_user_has_correct_fillable_attributes(): void
    {
        $user = new User();

        $expected = ['name', 'email', 'role', 'password'];

        $this->assertEquals($expected, $user->getFillable());
    }

    /**
     * Test user has correct hidden attributes
     */
    public function test_user_has_correct_hidden_attributes(): void
    {
        $user = new User();

        $expected = ['password', 'remember_token'];

        $this->assertEquals($expected, $user->getHidden());
    }

    /**
     * Test user role is cast to UserRole enum
     */
    public function test_role_is_cast_to_user_role_enum(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::ADMIN
        ]);

        $this->assertInstanceOf(UserRole::class, $user->role);
        $this->assertEquals(UserRole::ADMIN, $user->role);
    }

    /**
     * Test isAdmin method returns true for admin users
     */
    public function test_is_admin_returns_true_for_admin_users(): void
    {
        $adminUser = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicantUser = User::factory()->create(['role' => UserRole::APPLICANT]);

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($applicantUser->isAdmin());
    }

    /**
     * Test isApplicant method returns true for applicant users
     */
    public function test_is_applicant_returns_true_for_applicant_users(): void
    {
        $adminUser = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicantUser = User::factory()->create(['role' => UserRole::APPLICANT]);

        $this->assertFalse($adminUser->isApplicant());
        $this->assertTrue($applicantUser->isApplicant());
    }

    /**
     * Test admins scope filters admin users only
     */
    public function test_admins_scope_filters_admin_users_only(): void
    {
        User::factory()->create(['role' => UserRole::ADMIN, 'email' => 'admin@test.com']);
        User::factory()->create(['role' => UserRole::APPLICANT, 'email' => 'applicant@test.com']);
        User::factory()->create(['role' => UserRole::ADMIN, 'email' => 'admin2@test.com']);

        $admins = User::admins()->get();

        $this->assertCount(2, $admins);
        $this->assertTrue($admins->every(fn($user) => $user->role === UserRole::ADMIN));
    }

    /**
     * Test applicants scope filters applicant users only
     */
    public function test_applicants_scope_filters_applicant_users_only(): void
    {
        User::factory()->create(['role' => UserRole::ADMIN, 'email' => 'admin@test.com']);
        User::factory()->create(['role' => UserRole::APPLICANT, 'email' => 'applicant@test.com']);
        User::factory()->create(['role' => UserRole::APPLICANT, 'email' => 'applicant2@test.com']);

        $applicants = User::applicants()->get();

        $this->assertCount(2, $applicants);
        $this->assertTrue($applicants->every(fn($user) => $user->role === UserRole::APPLICANT));
    }

    /**
     * Test user has forms relationship
     */
    public function test_user_has_forms_relationship(): void
    {
        $user = new User();

        $this->assertInstanceOf(HasMany::class, $user->forms());
    }

    /**
     * Test forms relationship returns user's forms
     */
    public function test_forms_relationship_returns_users_forms(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Form::factory()->create(['user_id' => $user->id, 'name' => 'Form 1']);
        Form::factory()->create(['user_id' => $user->id, 'name' => 'Form 2']);
        Form::factory()->create(['user_id' => $otherUser->id, 'name' => 'Other Form']);

        $userForms = $user->forms;

        $this->assertCount(2, $userForms);
        $this->assertTrue($userForms->every(fn($form) => $form->user_id === $user->id));
    }

    /**
     * Test password is automatically hashed
     */
    public function test_password_is_automatically_hashed(): void
    {
        $user = User::factory()->create(['password' => 'plain-password']);

        $this->assertTrue(Hash::check('plain-password', $user->password));
        $this->assertNotEquals('plain-password', $user->password);
    }

    /**
     * Test user can be created with all required fields
     */
    public function test_user_can_be_created_with_required_fields(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => UserRole::APPLICANT,
            'password' => 'password123'
        ];

        $user = User::create($userData);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals(UserRole::APPLICANT, $user->role);
        $this->assertTrue(Hash::check('password123', $user->password));
    }
}
