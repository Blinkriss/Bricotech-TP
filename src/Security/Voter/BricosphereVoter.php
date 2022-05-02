<?php

namespace App\Security\Voter;

use App\Entity\Bricosphere;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BricosphereVoter extends Voter
{
    protected function supports($attribute, $bricosphere)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $bricosphere instanceof \App\Entity\Bricosphere;
    }

    protected function voteOnAttribute($attribute, $bricosphere, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (null == $bricosphere->getCreator()){
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                return $this->canEdit($bricosphere, $user);
                break;
            case 'DELETE':
                return $this->canDelete($bricosphere, $user);
                break;
        }
        return false;
    }

    private function canEdit(Bricosphere $bricosphere, User $user){
        return $user == $bricosphere->getCreator();
    }

    private function canDelete(Bricosphere $bricosphere, User $user){
        return $user == $bricosphere->getCreator();
    }
}
