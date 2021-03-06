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
            ['Jakub Testowy', 'kuba', '123456', 'kuba@example.com', ['ROLE_ADMIN'], md5(random_int(1, 1000).microtime())],
            ['Jan Kowalski', 'jan_admin', 'kitten', 'jane_admin@example.com', ['ROLE_ADMIN'], md5(random_int(1, 1000).microtime())],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@example.com', ['ROLE_ADMIN'], null],
            ['Kate Born', 'kate_admin', 'kitten', 'kate_admin@example.com', ['ROLE_ADMIN'], null],
            ['John Teapot', 'john_user', 'kitten', 'john_user@example.com', ['ROLE_USER'], md5(random_int(1, 1000).microtime())],
        ];
    }

    public static function getRandomUserReferenceId()
    {
        $ids = [];
        foreach (self::getUserData() as $data) {
            $ids[] = $data[1];
        }
        return $ids[random_int(0, count($ids)-1)];
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach (self::getUserData() as [$fullname, $username, $password, $email, $roles, $apiToken]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setApiToken($apiToken);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }
}
