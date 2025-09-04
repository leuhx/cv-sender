<?php

namespace Tests\Feature\Controllers\Admin;

use App\Enums\UserRole;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFormControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test admin can view all forms
     */
    public function test_admin_can_view_all_forms(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $applicant1 = User::factory()->create(['role' => UserRole::APPLICANT]);
        $applicant2 = User::factory()->create(['role' => UserRole::APPLICANT]);

        Form::factory()->create(['user_id' => $applicant1->id, 'name' => 'Form 1']);
        Form::factory()->create(['user_id' => $applicant2->id, 'name' => 'Form 2']);

        $response = $this->actingAs($admin)->get('/admin/forms');

        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Index')
                ->has('forms.data', 2)
        );
    }

    /**
     * Test admin can filter forms by user
     */
    public function test_admin_can_filter_forms_by_user(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $applicant1 = User::factory()->create(['role' => UserRole::APPLICANT, 'name' => 'João']);
        $applicant2 = User::factory()->create(['role' => UserRole::APPLICANT, 'name' => 'Maria']);

        Form::factory()->create(['user_id' => $applicant1->id]);
        Form::factory()->create(['user_id' => $applicant2->id]);

        $response = $this->actingAs($admin)->get('/admin/forms?user=' . $applicant1->id);

        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Index')
                ->has('forms.data', 1)
                ->where('forms.data.0.user_id', $applicant1->id)
        );
    }

    /**
     * Test admin can search forms by name
     */
    public function test_admin_can_search_forms_by_name(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);

        Form::factory()->create(['user_id' => $applicant->id, 'name' => 'João Silva']);
        Form::factory()->create(['user_id' => $applicant->id, 'name' => 'Maria Santos']);

        $response = $this->actingAs($admin)->get('/admin/forms?search=João');

        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Index')
                ->has('forms.data', 1)
                ->where('forms.data.0.name', 'João Silva')
        );
    }

    /**
     * Test admin can view specific form
     */
    public function test_admin_can_view_specific_form(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);
        $form = Form::factory()->create([
            'user_id' => $applicant->id,
            'name' => 'Test Form'
        ]);

        $response = $this->actingAs($admin)->get("/admin/forms/{$form->id}");

        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Show')
                ->where('form.name', 'Test Form')
        );
    }

    /**
     * Test admin can download CV files
     */
    public function test_admin_can_download_cv_files(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);

        // Create a fake CV file and store it
        Storage::disk('public')->put('cvs/test-cv.pdf', 'fake-pdf-content');

        $form = Form::factory()->create([
            'user_id' => $applicant->id,
            'cv_path' => 'cvs/test-cv.pdf'
        ]);

        $response = $this->actingAs($admin)->get("/admin/forms/{$form->id}/download-cv");

        // Since we're using fake storage, the download will return empty content
        // but we can verify the route works and doesn't throw 404
        $response->assertOk();
    }

    /**
     * Test admin can export forms to CSV
     */
    public function test_admin_can_export_forms_to_csv(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT, 'name' => 'João Silva']);

        Form::factory()->create([
            'user_id' => $applicant->id,
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'position' => 'Desenvolvedor'
        ]);

        $response = $this->actingAs($admin)->get('/admin/forms/export');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="forms.csv"');

        // Check CSV content
        $content = $response->getContent();
        $this->assertStringContainsString('João Silva', $content);
        $this->assertStringContainsString('joao@example.com', $content);
        $this->assertStringContainsString('Desenvolvedor', $content);
    }

    /**
     * Test admin can delete forms
     */
    public function test_admin_can_delete_forms(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);
        $form = Form::factory()->create(['user_id' => $applicant->id]);

        $response = $this->actingAs($admin)->delete("/admin/forms/{$form->id}");

        $response->assertRedirect('/admin/forms');
        $response->assertSessionHas('success', 'Formulário deletado com sucesso!');
        $this->assertDatabaseMissing('forms', ['id' => $form->id]);
    }

    /**
     * Test applicant cannot access admin routes
     */
    public function test_applicant_cannot_access_admin_routes(): void
    {
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);
        $form = Form::factory()->create(['user_id' => $applicant->id]);

        $this->actingAs($applicant)->get('/admin/forms')->assertForbidden();
        $this->actingAs($applicant)->get("/admin/forms/{$form->id}")->assertForbidden();
        $this->actingAs($applicant)->get("/admin/forms/{$form->id}/download-cv")->assertForbidden();
        $this->actingAs($applicant)->get('/admin/forms/export')->assertForbidden();
        $this->actingAs($applicant)->delete("/admin/forms/{$form->id}")->assertForbidden();
    }

    /**
     * Test unauthenticated users cannot access admin routes
     */
    public function test_unauthenticated_users_cannot_access_admin_routes(): void
    {
        $form = Form::factory()->create();

        $this->get('/admin/forms')->assertRedirect('/login');
        $this->get("/admin/forms/{$form->id}")->assertRedirect('/login');
        $this->get("/admin/forms/{$form->id}/download-cv")->assertRedirect('/login');
        $this->get('/admin/forms/export')->assertRedirect('/login');
        $this->delete("/admin/forms/{$form->id}")->assertRedirect('/login');
    }

    /**
     * Test admin forms index includes pagination
     */
    public function test_admin_forms_index_includes_pagination(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);

        // Create 25 forms to test pagination
        Form::factory()->count(25)->create(['user_id' => $applicant->id]);

        $response = $this->actingAs($admin)->get('/admin/forms');

        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Index')
                ->has('forms.data', 15) // Default per page is 15
                ->has('forms.links')
        );
    }

    /**
     * Test admin can view forms with user information
     */
    public function test_admin_can_view_forms_with_user_information(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicant = User::factory()->create([
            'role' => UserRole::APPLICANT,
            'name' => 'João Silva',
            'email' => 'joao@example.com'
        ]);

        Form::factory()->create(['user_id' => $applicant->id, 'name' => 'Form Test']);

        $response = $this->actingAs($admin)->get('/admin/forms');

        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Index')
                ->has('forms.data', 1)
                ->where('forms.data.0.user.name', 'João Silva')
                ->where('forms.data.0.user.email', 'joao@example.com')
        );
    }

    /**
     * Test admin forms index provides statistics
     */
    public function test_admin_forms_index_provides_statistics(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $applicant1 = User::factory()->create(['role' => UserRole::APPLICANT]);
        $applicant2 = User::factory()->create(['role' => UserRole::APPLICANT]);

        Form::factory()->count(3)->create(['user_id' => $applicant1->id]);
        Form::factory()->count(2)->create(['user_id' => $applicant2->id]);

        $response = $this->actingAs($admin)->get('/admin/forms');

        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Index')
                ->where('stats.totalForms', 5)
                ->where('stats.totalUsers', 2)
        );
    }

    /**
     * Test download returns 404 for non-existent form
     */
    public function test_download_returns_404_for_non_existent_form(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($admin)->get('/admin/forms/999/download-cv');

        $response->assertNotFound();
    }

    /**
     * Test download returns 404 for missing CV file
     */
    public function test_download_returns_404_for_missing_cv_file(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);

        $form = Form::factory()->create([
            'user_id' => $applicant->id,
            'cv_path' => 'non-existent-file.pdf'
        ]);

        $response = $this->actingAs($admin)->get("/admin/forms/{$form->id}/download-cv");

        $response->assertNotFound();
    }
}
