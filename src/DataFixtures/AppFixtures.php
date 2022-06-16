<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('murat@hotmail.com');
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                '123456'
            )
        );
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user); //Modele göre sorgu hazırlanır
        $manager->flush(); //Sorgu çalıştırılır.
    }
}
