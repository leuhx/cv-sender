<?php

namespace Tests\Feature\Integration;

use App\Enums\UserRole;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FormWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test complete form submission workflow
     */
    public function test_complete_form_submission_workflow(): void
    {
        // Create applicant user
        $applicant = User::factory()->create([
            'role' => UserRole::APPLICANT,
            'name' => 'João Silva',
            'email' => 'joao@example.com'
        ]);

        // Login as applicant
        $this->actingAs($applicant);

        // Navigate to forms page
        $response = $this->get('/forms');
        $response->assertOk();
        $response->assertInertia(fn($page) => $page->component('Forms/Index'));

        // Navigate to create form page
        $response = $this->get('/forms/create');
        $response->assertOk();
        $response->assertInertia(fn($page) => $page->component('Forms/Create'));

        // Submit form with CV
        $cvFile = UploadedFile::fake()->create('joao-cv.pdf', 1000, 'application/pdf');
        $formData = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'phone' => '+55 11 99999-9999',
            'position' => 'Desenvolvedor PHP',
            'education' => 'Bacharelado em Ciência da Computação',
            'observations' => 'Experiência com Laravel e Vue.js',
            'cv_file' => $cvFile,
        ];

        $response = $this->post('/forms', $formData);
        $response->assertRedirect('/forms');
        $response->assertSessionHas('success', 'Formulário enviado com sucesso!');

        // Verify form was created in database
        $form = Form::where('user_id', $applicant->id)->first();
        $this->assertNotNull($form);
        $this->assertEquals('João Silva', $form->name);
        $this->assertEquals('joao@example.com', $form->email);

        // Verify CV file was stored
        Storage::assertExists('public/' . $form->cv_path);

        // View the created form
        $response = $this->get("/forms/{$form->id}");
        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Forms/Show')
                ->where('form.name', 'João Silva')
        );

        // Edit the form
        $response = $this->get("/forms/{$form->id}/edit");
        $response->assertOk();
        $response->assertInertia(fn($page) => $page->component('Forms/Edit'));

        // Update the form
        $updateData = [
            'name' => 'João Silva Santos',
            'email' => 'joao.santos@example.com',
            'position' => 'Senior Developer',
            'education' => 'Mestrado',
            'observations' => 'Experiência com Laravel, Vue.js e Docker',
            '_method' => 'PATCH'
        ];

        $response = $this->post("/forms/{$form->id}", $updateData);
        $response->assertRedirect('/forms');
        $response->assertSessionHas('success', 'Formulário atualizado com sucesso!');

        // Verify form was updated
        $form->refresh();
        $this->assertEquals('João Silva Santos', $form->name);
        $this->assertEquals('joao.santos@example.com', $form->email);
        $this->assertEquals('Senior Developer', $form->position);
    }

    /**
     * Test admin workflow for managing forms
     */
    public function test_admin_workflow_for_managing_forms(): void
    {
        // Create admin and applicants
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicant1 = User::factory()->create([
            'role' => UserRole::APPLICANT,
            'name' => 'João Silva'
        ]);
        $applicant2 = User::factory()->create([
            'role' => UserRole::APPLICANT,
            'name' => 'Maria Santos'
        ]);

        // Create forms for applicants
        $form1 = Form::factory()->create([
            'user_id' => $applicant1->id,
            'name' => 'João Silva',
            'position' => 'Desenvolvedor'
        ]);
        $form2 = Form::factory()->create([
            'user_id' => $applicant2->id,
            'name' => 'Maria Santos',
            'position' => 'Designer'
        ]);

        // Store CV files
        $cvFile1 = UploadedFile::fake()->create('joao-cv.pdf', 1000, 'application/pdf');
        $cvFile2 = UploadedFile::fake()->create('maria-cv.pdf', 1000, 'application/pdf');
        $form1->update(['cv_path' => $cvFile1->store('cvs', 'public')]);
        $form2->update(['cv_path' => $cvFile2->store('cvs', 'public')]);

        // Login as admin
        $this->actingAs($admin);

        // View all forms
        $response = $this->get('/admin/forms');
        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Index')
                ->has('forms.data', 2)
                ->where('stats.totalForms', 2)
                ->where('stats.totalUsers', 2)
        );

        // Filter forms by user
        $response = $this->get('/admin/forms?user=' . $applicant1->id);
        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Index')
                ->has('forms.data', 1)
                ->where('forms.data.0.user.name', 'João Silva')
        );

        // Search forms by name
        $response = $this->get('/admin/forms?search=Maria');
        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Index')
                ->has('forms.data', 1)
                ->where('forms.data.0.name', 'Maria Santos')
        );

        // View specific form
        $response = $this->get("/admin/forms/{$form1->id}");
        $response->assertOk();
        $response->assertInertia(
            fn($page) =>
            $page->component('Admin/Forms/Show')
                ->where('form.name', 'João Silva')
        );

        // Download CV
        $response = $this->get("/admin/forms/{$form1->id}/download-cv");
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');

        // Export forms to CSV
        $response = $this->get('/admin/forms/export');
        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $content = $response->getContent();
        $this->assertStringContainsString('João Silva', $content);
        $this->assertStringContainsString('Maria Santos', $content);

        // Delete a form
        $response = $this->delete("/admin/forms/{$form2->id}");
        $response->assertRedirect('/admin/forms');
        $response->assertSessionHas('success', 'Formulário excluído com sucesso!');
        $this->assertDatabaseMissing('forms', ['id' => $form2->id]);
    }

    /**
     * Test role-based access control integration
     */
    public function test_role_based_access_control_integration(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);
        $form = Form::factory()->create(['user_id' => $applicant->id]);

        // Admin cannot access applicant routes
        $this->actingAs($admin);
        $this->get('/forms')->assertForbidden();
        $this->get('/forms/create')->assertForbidden();
        $this->get("/forms/{$form->id}")->assertForbidden();
        $this->get("/forms/{$form->id}/edit")->assertForbidden();

        // Applicant cannot access admin routes
        $this->actingAs($applicant);
        $this->get('/admin/forms')->assertForbidden();
        $this->get("/admin/forms/{$form->id}")->assertForbidden();
        $this->get("/admin/forms/{$form->id}/download-cv")->assertForbidden();
        $this->get('/admin/forms/export')->assertForbidden();

        // Applicant can only access their own forms
        $otherApplicant = User::factory()->create(['role' => UserRole::APPLICANT]);
        $otherForm = Form::factory()->create(['user_id' => $otherApplicant->id]);

        $this->actingAs($applicant);
        $this->get("/forms/{$otherForm->id}")->assertForbidden();
        $this->get("/forms/{$otherForm->id}/edit")->assertForbidden();
        $this->patch("/forms/{$otherForm->id}", [])->assertForbidden();
        $this->delete("/forms/{$otherForm->id}")->assertForbidden();
    }

    /**
     * Test file upload and download integration
     */
    public function test_file_upload_and_download_integration(): void
    {
        $applicant = User::factory()->create(['role' => UserRole::APPLICANT]);
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        // Create form with CV as applicant
        $this->actingAs($applicant);

        $cvFile = UploadedFile::fake()->create('test-cv.pdf', 1000, 'application/pdf');
        $formData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+55 11 88888-8888',
            'position' => 'Developer',
            'education' => 'Bacharelado em Ciência da Computação',
            'cv_file' => $cvFile,
        ];

        $response = $this->post('/forms', $formData);
        $response->assertRedirect('/forms');

        $form = Form::where('user_id', $applicant->id)->first();
        Storage::assertExists('public/' . $form->cv_path);

        // Admin can download the CV
        $this->actingAs($admin);
        $response = $this->get("/admin/forms/{$form->id}/download-cv");
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');

        // Update CV file as applicant
        $this->actingAs($applicant);
        $newCvFile = UploadedFile::fake()->create('updated-cv.pdf', 1200, 'application/pdf');

        $updateData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+55 11 88888-8888',
            'position' => 'Senior Developer',
            'education' => 'Pós-graduação/Especialização',
            'cv_file' => $newCvFile,
            '_method' => 'PATCH'
        ];

        $response = $this->post("/forms/{$form->id}", $updateData);
        $response->assertRedirect('/forms');

        $form->refresh();
        Storage::assertExists('public/' . $form->cv_path);

        // Admin can download the updated CV
        $this->actingAs($admin);
        $response = $this->get("/admin/forms/{$form->id}/download-cv");
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
