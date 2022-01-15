<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixture extends Fixture
{


    private UserPasswordHasherInterface $password_hasher;

    public function __construct(UserPasswordHasherInterface $password_hasher)
    {
        $this->password_hasher = $password_hasher;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail('foo@mail.ru');
        $user1->setPassword($this->password_hasher->hashPassword($user1, '1234'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('bar@mail.ru');
        $user2->setPassword($this->password_hasher->hashPassword($user2, '1234'));
        $manager->persist($user2);
        $manager->flush();
    }
}