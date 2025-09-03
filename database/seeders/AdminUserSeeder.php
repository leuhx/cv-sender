<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador padrão
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'role' => UserRole::ADMIN,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Criar usuário aplicante de exemplo
        User::firstOrCreate(
            ['email' => 'applicant@example.com'],
            [
                'name' => 'Candidato Exemplo',
                'role' => UserRole::APPLICANT,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuários criados:');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Applicant: applicant@example.com / password');
    }
}
