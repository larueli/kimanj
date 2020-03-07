<?php

namespace App\Security\Voter;

use App\Entity\Question;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class QuestionVoter extends Voter
{
    const EDIT = 'edit';
    const VIEW = "view";

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Question;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $user instanceof UserInterface && ( is_null($subject->getId()) || $subject->getUuid() === $user->getUsername() );
                break;
            case self::VIEW:
                return true;
                break;
        }

        return false;
    }
}
