<?php

namespace App\Enum;

enum CourrierStatut: string
{
    case RECU = 'recu';
    case EN_COURS = 'en_cours';
    case TRAITE = 'traite';
    case ARCHIVE = 'archive';

    public function getLabel(): string
    {
        return match($this) {
            self::RECU => 'Reçu',
            self::EN_COURS => 'En cours',
            self::TRAITE => 'Traité',
            self::ARCHIVE => 'Archivé',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::RECU => 'secondary',
            self::EN_COURS => 'warning',
            self::TRAITE => 'success',
            self::ARCHIVE => 'dark',
        };
    }
}
