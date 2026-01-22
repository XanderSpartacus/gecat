<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Admin user 1
        $admin = new User();
        $admin->setEmail('admin@gecat.ga');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));

        $manager->persist($admin);

        // Admin user 2
        $admin = new User();
        $admin->setEmail('gestionnaire@gecat.ga');
        $admin->setRoles(['ROLE_GESTIONNAIRE']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));

        $manager->persist($admin);

        $manager->flush();
    }
}
