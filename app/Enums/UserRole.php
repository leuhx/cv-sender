<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case APPLICANT = 'applicant';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::APPLICANT => 'Candidato',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isApplicant(): bool
    {
        return $this === self::APPLICANT;
    }
}
