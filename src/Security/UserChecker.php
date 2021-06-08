<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    private $onlyAdmin;

    /**
     * UserChecker constructor.
     * @param  bool  $onlyAdmin
     */
    public function __construct(bool $onlyAdmin = false)
    {
        $this->onlyAdmin = $onlyAdmin;
    }

    /**
     * Przed uwierzytelnieniem
     * @param  UserInterface  $user
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }
    }

    /**
     * Po uwierzytelnieniu
     * @param  UserInterface  $user
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($this->onlyAdmin && !$user->isAdmin()) {
            throw new CustomUserMessageAccountStatusException('Logować mogą się tylko administratorzy');
        }
    }
}
