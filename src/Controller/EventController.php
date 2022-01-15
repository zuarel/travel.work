<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /** @Route("/api/events", methods={"GET"}) */
    public function list()
    {
        return $this->json([]);
    }

    /** @Route("/api/events", methods={"POST"}) */
    public function create()
    {
        return $this->json([]);
    }

    /** @Route("/api/events/{id}", methods={"GET"}) */
    public function getOne(int $id)
    {
        return $this->json([]);
    }

    /** @Route("/api/events/{id}", methods={"DELETE"}) */
    public function delete(int $id)
    {
        return $this->json([]);
    }
}
