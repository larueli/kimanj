<?php

namespace App\Security\Voter;

use App\Entity\Reponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ReponseVoter extends Voter
{
    const EDIT = 'edit';
    const VIEW = "view";
    private $security;

    /**
     * ReponseVoter constructor.
     *
     * @param $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof Reponse;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $user instanceof UserInterface && ( is_null($subject->getId()) || $subject->getUuid() === $user->getUsername() );
                break;
            case self::VIEW:
                return ( ( $subject->getQuestion()->getReponsesPubliques() || $this->security->isGranted("edit",
                                                                                                         $subject->getQuestion()) ) && !$subject->getQuestion()
                            ->getReponsesAnonymes() ) || $this->security->isGranted("edit", $subject);
                break;
        }

        return false;
    }
}
