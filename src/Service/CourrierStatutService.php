<?php

namespace App\Service;

use App\Entity\Courrier;
use App\Enum\CourrierStatut;

class CourrierStatutService
{
    /*
     * Définit les transitions autorisées pour chaque statut.
     */
    public function getAllowedTransitions(CourrierStatut $currentStatut): array
    {
        return match($currentStatut) {
            CourrierStatut::RECU => [CourrierStatut::EN_COURS, CourrierStatut::ARCHIVE],
            CourrierStatut::EN_COURS => [CourrierStatut::TRAITE, CourrierStatut::ARCHIVE],
            CourrierStatut::TRAITE => [CourrierStatut::ARCHIVE],
            CourrierStatut::ARCHIVE => [CourrierStatut::EN_COURS], // Possibilité de ressortir d'archive
        };
    }

    /*
     * Vérifie si un courrier est vérrouillé (plus modifiable par les gestionnaires).
     */
    public function isLocked(Courrier $courrier): bool
    {
        return $courrier->getStatut() === CourrierStatut::ARCHIVE;
    }

    /*
     * Vérifie si une transition est possible
     */
    public function canTransitionTo(Courrier $courrier, string $newStatut): bool
    {
        $currentStatut = CourrierStatut::tryFrom($courrier->getStatut());
        $targetStatus = CourrierStatut::tryFrom($newStatut);

        if (!$currentStatut || $targetStatus) {
            return false;
        }

        return in_array($targetStatus, $this->getAllowedTransitions($currentStatut), true);
    }
}
