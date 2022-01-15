<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class UserService
{

    private \Doctrine\Persistence\ObjectManager $em;
    private \Doctrine\Persistence\ObjectRepository $repository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->repository = $doctrine->getRepository(User::class);
        $this->em = $doctrine->getManager();
    }

    public function findById($id): User
    {
        return $this->repository->find($id);
    }

    public function find($filters = []): array
    {
        return $this->repository->findBy($filters);
    }

    public function findOne($filters = []): ?User
    {
        return $this->repository->findOneBy($filters);
    }
}