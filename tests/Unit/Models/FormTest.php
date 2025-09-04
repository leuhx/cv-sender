<?php

namespace Tests\Unit\Models;

use App\Enums\UserRole;
use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test form has correct fillable attributes
     */
    public function test_form_has_correct_fillable_attributes(): void
    {
        $form = new Form();

        $expected = [
            'user_id',
            'name',
            'email',
            'phone',
            'position',
            'education',
            'observations',
            'cv_path',
        ];

        $this->assertEquals($expected, $form->getFillable());
    }

    /**
     * Test form has correct table name
     */
    public function test_form_has_correct_table_name(): void
    {
        $form = new Form();

        $this->assertEquals('forms', $form->getTable());
    }

    /**
     * Test form belongs to user
     */
    public function test_form_belongs_to_user(): void
    {
        $form = new Form();

        $this->assertInstanceOf(BelongsTo::class, $form->user());
    }

    /**
     * Test form user relationship returns correct user
     */
    public function test_form_user_relationship_returns_correct_user(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $form->user->id);
        $this->assertEquals($user->name, $form->user->name);
    }

    /**
     * Test forUser scope filters forms for specific user
     */
    public function test_for_user_scope_filters_forms_for_specific_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Form::factory()->create(['user_id' => $user1->id, 'name' => 'Form 1']);
        Form::factory()->create(['user_id' => $user1->id, 'name' => 'Form 2']);
        Form::factory()->create(['user_id' => $user2->id, 'name' => 'Form 3']);

        $user1Forms = Form::forUser($user1->id)->get();

        $this->assertCount(2, $user1Forms);
        $this->assertTrue($user1Forms->every(fn($form) => $form->user_id === $user1->id));
    }

    /**
     * Test all scope includes user relationship
     */
    public function test_all_scope_includes_user_relationship(): void
    {
        $user = User::factory()->create();
        Form::factory()->create(['user_id' => $user->id]);

        $forms = Form::with('user')->first();

        // Check if user relationship is loaded
        $this->assertTrue($forms->relationLoaded('user'));
        $this->assertEquals($user->id, $forms->user->id);
    }

    /**
     * Test form can be created with all required fields
     */
    public function test_form_can_be_created_with_required_fields(): void
    {
        $user = User::factory()->create();

        $formData = [
            'user_id' => $user->id,
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'phone' => '+55 11 99999-9999',
            'position' => 'Desenvolvedor',
            'education' => 'Bacharelado em Ciência da Computação',
            'observations' => 'Experiência com Laravel e Vue.js',
            'cv_path' => 'cvs/joao-silva-cv.pdf',
        ];

        $form = Form::create($formData);

        $this->assertEquals($user->id, $form->user_id);
        $this->assertEquals('João Silva', $form->name);
        $this->assertEquals('joao@example.com', $form->email);
        $this->assertEquals('+55 11 99999-9999', $form->phone);
        $this->assertEquals('Desenvolvedor', $form->position);
        $this->assertEquals('Bacharelado em Ciência da Computação', $form->education);
        $this->assertEquals('Experiência com Laravel e Vue.js', $form->observations);
        $this->assertEquals('cvs/joao-silva-cv.pdf', $form->cv_path);
    }

    /**
     * Test form timestamps are set automatically
     */
    public function test_form_timestamps_are_set_automatically(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->assertNotNull($form->created_at);
        $this->assertNotNull($form->updated_at);
    }

    /**
     * Test form can be updated
     */
    public function test_form_can_be_updated(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create([
            'user_id' => $user->id,
            'name' => 'Original Name'
        ]);

        $form->update(['name' => 'Updated Name']);

        $this->assertEquals('Updated Name', $form->fresh()->name);
    }

    /**
     * Test form can be deleted
     */
    public function test_form_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);
        $formId = $form->id;

        $form->delete();

        $this->assertNull(Form::find($formId));
    }

    /**
     * Test form attributes are properly accessible
     */
    public function test_form_attributes_are_properly_accessible(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Name',
            'email' => 'test@example.com',
            'phone' => '+55 11 98765-4321',
            'position' => 'Test Position',
            'education' => 'Test Education',
            'observations' => 'Test Observations',
            'cv_path' => 'test/path.pdf'
        ]);

        $this->assertEquals($user->id, $form->user_id);
        $this->assertEquals('Test Name', $form->name);
        $this->assertEquals('test@example.com', $form->email);
        $this->assertEquals('+55 11 98765-4321', $form->phone);
        $this->assertEquals('Test Position', $form->position);
        $this->assertEquals('Test Education', $form->education);
        $this->assertEquals('Test Observations', $form->observations);
        $this->assertEquals('test/path.pdf', $form->cv_path);
    }
}
