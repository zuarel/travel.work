<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Firebase\JWT\JWT;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{

    private \Doctrine\Persistence\ObjectRepository $repository;
    private \Doctrine\Persistence\ObjectManager $em;
    private UserPasswordHasherInterface $password_hasher;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $password_hasher)
    {
        $this->repository = $doctrine->getRepository(User::class);
        $this->em = $doctrine->getManager();
        $this->password_hasher = $password_hasher;
    }

    public function login($username, $password)
    {

        $user = $this->repository->findOneBy(['email' => $username]);

        if (!$user) {
            throw new \Error('Пользователь с такой почтой или паролем не найден');
        }

        $is_match = $this->password_hasher->isPasswordValid($user, $password);

        if (!$is_match) {
            throw new \Error('Пользователь с такой почтой или паролем не найден');
        }

        $key = 'jwt_secret_key';

        $token = JWT::encode([
            'user_id' => $user->getId(),
            'username' => $user->getUserIdentifier(),
            'exp' => time() + 60 * 15 // 15 minutes
        ], $key);

        return $token;
    }

    public function register($username, $password): User
    {
        $existed_user = $this->repository->findOneBy(['email' => $username]);

        if ($existed_user) {
            throw new \Error('Пользователь с такой почтой уже существует');
        }

        $user = new User();
        $user->setEmail($username);

        $hashed_password = $this->password_hasher->hashPassword($user, $password);
        $user->setPassword($hashed_password);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }
}