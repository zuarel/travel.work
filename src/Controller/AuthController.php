<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/api/auth/register", name="auth", methods={"POST"})
     */
    public function register(UserPasswordHasherInterface $password_hasher, Request $request): Response
    {

        $content = json_decode($request->getContent(), true);

        $username = $content['username'];
        $password = $content['password'];

        $repository = $this->doctrine->getRepository(User::class);

        $existed_user = $repository->findOneBy(['email' => $username]);

        if($existed_user) {
            return $this->json([
                'message' => 'Пользователь с такой почтой уже существует'
            ], 500);
        }

        $user = new User();
        $user->setEmail($username);

        $hashed_password = $password_hasher->hashPassword($user, $password);
        $user->setPassword($hashed_password);
        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();


        return $this->json([
            'status' => 'ok',
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier()
        ]);
    }
}
