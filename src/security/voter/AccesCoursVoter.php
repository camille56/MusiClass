<?php

namespace App\security\voter;

use App\Entity\Cours;
use App\Entity\Formation;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AccesCoursVoter extends Voter
{

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === 'ACCESS_COURS' && $subject instanceof Cours;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        //Récupération du user grace au token.
        $user = $token->getUser();

        //On vérifie que l'utilisateur est connecté et que le sujet est valide.
        if (!$user instanceof User || !$subject instanceof Cours){
            return false;
        }

        $formation = $subject->getFormation();

        if (!$formation){
            return false;
        }

        return $user->getFormations()->contains($formation);

    }
}