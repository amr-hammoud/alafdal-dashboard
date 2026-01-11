<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => 'CEO / Admin',
            self::EDITOR => 'Employee / Editor',
        };
    }
}
