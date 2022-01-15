<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{

    private \Doctrine\Persistence\ObjectRepository $repository;
    private \Doctrine\Persistence\ObjectManager $em;
    private UserPasswordHasherInterface $password_hasher;
    private TokenService $token_service;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $password_hasher, TokenService $token_service)
    {
        $this->repository = $doctrine->getRepository(User::class);
        $this->em = $doctrine->getManager();
        $this->password_hasher = $password_hasher;
        $this->token_service = $token_service;
    }

    public function login($username, $password)
    {

        /** @var User $user */
        $user = $this->repository->findOneBy(['email' => $username]);

        if (!$user) {
            throw new \Error('Пользователь с такой почтой или паролем не найден');
        }

        $is_match = $this->password_hasher->isPasswordValid($user, $password);

        if (!$is_match) {
            throw new \Error('Пользователь с такой почтой или паролем не найден');
        }

        $token = $this->token_service->getToken([
            'user_id' => $user->getId(),
            'username' => $user->getUserIdentifier()
        ]);

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