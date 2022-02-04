<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $manager->persist(
            $this->createUser(
                name: 'Regular User',
                login: 'user',
                pass: 'userpass',
                role: User::ROLE_USER,
            )
        );
        $manager->persist(
            $this->createUser(
                name: 'Admin User',
                login: 'admin',
                pass: 'adminpass',
                role: User::ROLE_ADMIN,
            )
        );
        $manager->flush();
    }

    private function createUser(
        string $name,
        string $login,
        string $pass,
        int $role,
    ): User {
        $user = new User();
        $user->setName($name);
        $user->setLogin($login);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $pass)
        );
        $user->setRole($role);

        return $user;
    }
}
