<?php

namespace App\Security\Voter;

use App\Entity\Tool;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ToolVoter extends Voter
{
    protected function supports($attribute, $tool)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $tool instanceof \App\Entity\Tool;
    }

    protected function voteOnAttribute($attribute, $tool, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (null == $tool->getUser()){
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                return $this->canEdit($tool, $user);
                break;
            case 'DELETE':
                return $this->canDelete($tool, $user);
                break;
        }
        return false;
    }

    private function canEdit(Tool $tool, User $user){
        return $user == $tool->getUser();
    }

    private function canDelete(Tool $tool, User $user){
        return $user == $tool->getUser();
    }
}

