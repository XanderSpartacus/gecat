<?php

namespace App\Security\Voter;

use App\Entity\Courrier;
use App\Entity\User;
use App\Enum\CourrierStatut;
use App\Service\CourrierStatutService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CourrierVoter extends Voter
{
    public const VIEW = 'COURRIER_VIEW';
    public const EDIT = 'COURRIER_EDIT';
    public const DELETE = 'COURRIER_DELETE';

    public function __construct(
        private Security $security,
        private CourrierStatutService $statusService
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Courrier;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var Courrier $courrier */
        $courrier = $subject;

        // ROLE_ADMIN a tous les droits
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return match($attribute) {
            self::VIEW => true,
            self::EDIT => $this->canEdit($courrier, $user),
            self::DELETE => false, // Seul l'admin a le droit (géré au dessus)
            default => false,
        };
    }

    private function canEdit(Courrier $courrier, User $user): bool
    {
        // On demande au service si le courrier est dans un état modifiable
        if ($this->statusService->isLocked($courrier)) {
            return false; // Verrouillé pour les gestionnaires
        }

        // Sinon, tous les gestionnaires peuvent entrer en édition
        return $this->security->isGranted('ROLE_GESTIONNAIRE');
    }
}
