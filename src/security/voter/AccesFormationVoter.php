<?php

namespace App\security\voter;

use App\Entity\Formation;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AccesFormationVoter extends Voter
{

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {

        //Le voter doit retourner true si le voter doit voter et false s'il s'abstient.
        return $attribute === 'ACCESS_FORMATION' && $subject instanceof Formation;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        //Récupération du user grace au token.
        $user = $token->getUser();

        //On vérifie que l'utilisateur est connecté et que le sujet est valide.
        if (!$user instanceof User || !$subject instanceof Formation){
            return false;
        }


        // Vérifier si l'utilisateur a accès à la formation.
        return $user->getFormations()->contains($subject);


    }
}