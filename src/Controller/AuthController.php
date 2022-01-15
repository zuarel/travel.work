<?php

namespace App\Controller;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{

    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/api/auth/register", name="auth", methods={"POST"})
     */
    public function register(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);

            $username = $content['username'];
            $password = $content['password'];

            $user = $this->service->register($username, $password);

            return $this->json([
                'id' => $user->getId(),
                'email' => $user->getUserIdentifier()
            ]);
        } catch (\Error $error) {
            return $this->json(['message' => $error->getMessage()], 500);
        }
    }

    /**
     * @Route("/api/auth/login", methods={"POST"})
     */
    public function login(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);

            $username = $content['username'];
            $password = $content['password'];

            $token = $this->service->login($username, $password);

            return $this->json([
                'token' => $token
            ]);
        } catch (\Error $error) {
            return $this->json(['message' => $error->getMessage()], 500);
        }
    }
}
