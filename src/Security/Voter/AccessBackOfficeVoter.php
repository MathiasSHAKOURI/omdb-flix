<?php

namespace App\Security\Voter;

use DateTimeZone;
use DateTimeImmutable;
use App\Entity\AccessBackOffice;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AccessBackOfficeVoter extends Voter
{
    public const ACCESS = "BACK_OFFICE_ACCESS";

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::ACCESS]);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ACCESS:

                $now = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
                $hour = $now->format('H');
//      
                if ($hour >= 23){
                    return false;
                } else {
                    return true;
                };
                break;
        }

        return false;
    }
}
