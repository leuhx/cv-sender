<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Form::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'position' => fake()->randomElement([
                'Desenvolvedor PHP',
                'Desenvolvedor Frontend',
                'Desenvolvedor Fullstack',
                'Designer UX/UI',
                'Analista de Sistemas',
                'Gerente de Projetos',
                'DevOps Engineer'
            ]),
            'education' => fake()->randomElement([
                'Ensino Fundamental',
                'Ensino Médio',
                'Técnico em Informática',
                'Tecnólogo em Análise e Desenvolvimento de Sistemas',
                'Curso Superior de Tecnologia em Redes de Computadores',
                'Bacharelado em Ciência da Computação',
                'Bacharelado em Sistemas de Informação',
                'Bacharelado em Engenharia de Software',
                'Pós-graduação/Especialização',
                'Mestrado',
                'Doutorado'
            ]),
            'observations' => fake()->optional()->paragraph(),
            'cv_path' => 'cvs/' . fake()->uuid() . '.pdf',
        ];
    }

    /**
     * Create form for a specific user
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Create form for an applicant user
     */
    public function forApplicant(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => User::factory()->create(['role' => UserRole::APPLICANT])->id,
        ]);
    }

    /**
     * Create form without observations
     */
    public function withoutObservations(): static
    {
        return $this->state(fn(array $attributes) => [
            'observations' => null,
        ]);
    }

    /**
     * Create form with specific CV path
     */
    public function withCvPath(string $path): static
    {
        return $this->state(fn(array $attributes) => [
            'cv_path' => $path,
        ]);
    }
}
