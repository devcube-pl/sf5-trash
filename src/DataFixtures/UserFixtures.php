<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * UserFixtures constructor.
     * @param  UserPasswordHasherInterface  $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    public static function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jakub Testowy', 'kuba', '123456', 'kuba@example.com', ['ROLE_ADMIN']],
            ['Jan Kowalski', 'jan_admin', 'kitten', 'jane_admin@example.com', ['ROLE_ADMIN']],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@example.com', ['ROLE_ADMIN']],
            ['Kate Born', 'kate_admin', 'kitten', 'kate_admin@example.com', ['ROLE_ADMIN']],
            ['John Teapot', 'john_user', 'kitten', 'john_user@example.com', ['ROLE_USER']],
        ];
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach (self::getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }
}
