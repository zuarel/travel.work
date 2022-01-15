<?php

namespace App\Service;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventService
{
    private \Doctrine\Persistence\ObjectManager $em;
    private \Doctrine\Persistence\ObjectRepository|EventRepository $repository;
    private UserService $user_service;
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine, UserService $user_service)
    {
        $this->repository = $doctrine->getRepository(Event::class);
        $this->em = $doctrine->getManager();
        $this->user_service = $user_service;
        $this->doctrine = $doctrine;
    }

    public function getListOwnAndOthersByUserId(int $user_id, $name = '')
    {
      return $this->repository->findAllByUserId($user_id, ['name' => $name]);
    }

    public function findOneById(int $id): ?Event
    {
        return $this->repository->find($id);
    }

    public function deleteById(int $id)
    {
       $event = $this->findOneById($id);
       $event->setStatus($event::STATUS_DELETED);
       $this->em->flush();
    }

    public function create($data = []): Event
    {
        if (empty($data['creator'])) {
            throw new \Error('Отсутствует значение creator');
        }

        $invited_user = $this->user_service->findOne(['email' => $data['invited_email']]);

        if (!$invited_user) {
            throw new \Error('Приглашенный пользователь не найден');
        }

        $creator = $this->user_service->findById($data['creator']);

        if (!$creator) {
            throw new \Error('Создатель встречи не найден');
        }

        $event = new Event();
        $event->setCreateAt(time());
        $event->setCreator($creator->getId());
        $event->setEndAt($data['end_at']);
        $event->setStartAt($data['start_at']);
        $event->setInvited($invited_user->getId());
        $event->setName($data['name']);

        $this->em->persist($event);
        $this->em->flush();

        return $event;
    }

    public function changeEventStatus(int $event_id, $status)
    {
        $event = $this->findOneById($event_id);

        if(!$event) {
            throw new \Error('Встреча не найдена');
        }

        if(!in_array($status, [Event::STATUS_CREATED, Event::STATUS_ACCEPTED, Event::STATUS_DECLINED])) {
            throw new \Error('Неверный статус встречи');
        }

        $event->setStatus($status);
        $this->em->flush();
    }
}