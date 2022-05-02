<?php

namespace App\Security\Voter;

use App\Entity\Booking;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingVoter extends Voter
{
    protected function supports($attribute, $booking)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $booking instanceof \App\Entity\Booking;
    }

    protected function voteOnAttribute($attribute, $booking, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (null == $booking->getBooker()){
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                return $this->canEdit($booking, $user);
                break;
            case 'DELETE':
                return $this->canDelete($booking, $user);
                break;
        }
        return false;
    }

    private function canEdit(Booking $booking, User $user){
        return $user == $booking->getBooker() || $user == $booking->getTool()->getUser();
    }

    private function canDelete(Booking $booking, User $user){
        return $user == $booking->getBooker() || $user == $booking->getTool()->getUser();
    }
}
