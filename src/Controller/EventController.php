<?php

namespace App\Controller;

use App\Service\EventService;
use App\Service\TokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    private EventService $service;
    private TokenService $token_service;

    public function __construct(EventService $service, TokenService $token_service)
    {
        $this->service = $service;
        $this->token_service = $token_service;
    }

    /** @Route("/api/events", methods={"GET"}) */
    public function list(Request $request)
    {
        try {
            $name = $request->query->get('name', '');
            $token_payload = $this->checkAuth($request);
            $events = $this->service->getListOwnAndOthersByUserId($token_payload->user_id, $name);

            return $this->json($events);
        } catch (\Error $error) {
            return $this->json(['message' => $error->getMessage()], 500);
        }
    }

    /** @Route("/api/events", methods={"POST"}) */
    public function create(Request $request): Response
    {
        try {
            $token_payload = $this->checkAuth($request);

            $content = json_decode($request->getContent(), true);

            if (empty($content['start_at']) || empty($content['end_at']) || empty($content['invited_email'])) {
                return $this->json(['message' => 'Отсутствуют обязательные поля'], 400);
            }

            $content['creator'] = $token_payload->user_id;

            $event = $this->service->create($content);

            return $this->json($event);
        } catch (\Error $error) {
            return $this->json(['message' => $error->getMessage()], 500);
        }
    }

    /** @Route("/api/events/{id}", methods={"GET"}) */
    public function getOne(int $id, Request $request): Response
    {
        try {
            $token_payload = $this->checkAuth($request);
            $event = $this->service->findOneById($id);

            if (!$event) {
                return $this->json(['message' => 'Встреча не найдена'], 404);
            }

            if (!$event->hasAccessForUser($token_payload->user_id)) {
                return $this->json(['message' => 'Встреча не найдена'], 403);
            }

            if (!$event->isNowOn()) {
                return $this->json(['message' => 'Встреча сейчас недоступна'], 403);
            }

            return $this->json($event);
        } catch (\Error $error) {
            return $this->json(['message' => $error->getMessage()], 500);
        }
    }

    /** @Route("/api/events/{id}", methods={"DELETE"}) */
    public function delete(int $id, Request $request): Response
    {
        try {
            $token_payload = $this->checkAuth($request);
            $event = $this->service->findOneById($id);

            if (!$event->isOwner($token_payload->user_id)) {
                return $this->json(['message' => 'Недостаточно прав'], 403);
            }

            $this->service->deleteById($id);
            return $this->json(['message' => 'ok']);
        } catch (\Error $error) {
            return $this->json(['message' => $error->getMessage()], 500);
        }
    }

    /** @Route("/api/events/{id}", methods={"PUT"}) */
    public function changeEventStatus(int $id, Request $request): Response
    {
        try {
            $token_payload = $this->checkAuth($request);
            $content = json_decode($request->getContent(), true);

            $event = $this->service->findOneById($id);

            if(!$event) {
                return $this->json(['message' => 'Встреча не найдена'], 404);
            }

            if($event->getInvited() !== $token_payload->user_id) {
                return $this->json(['message' => 'Встреча не найдена'], 403);
            }

            if(empty($content['status'])) {
                return $this->json(['message' => 'Не передан аргумент status'], 400);
            }

            $status = $content['status'];

            $this->service->changeEventStatus($id, $status);
            return $this->json(['message' => 'ok']);
        } catch (\Error $error) {
            return $this->json(['message' => $error->getMessage()], 500);
        }
    }

    private function checkAuth(Request $request)
    {
        $auth_header = $request->headers->get('Authorization');

        if (!$auth_header) {
            throw new \Error('Необходимо авторизоваться');
        }

        $token = explode('Bearer', $auth_header)[1];
        $token = trim($token);
        $token_payload = $this->token_service->verifyToken($token);
        return $token_payload;
    }
}
