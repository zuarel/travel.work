<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventsFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $event1 = new Event();
        $event1->setName('Встреча один');
        $event1->setCreator(1);
        $event1->setInvited(2);
        $event1->setStartAt(time());
        $event1->setEndAt(time() + 3600);
        $event1->setCreateAt(time());
        $manager->persist($event1);

        $event2 = new Event();
        $event2->setName('Встреча два');
        $event2->setCreator(1);
        $event2->setInvited(2);
        $event2->setStartAt(time() + 600);
        $event2->setEndAt(time() + 4200);
        $event2->setCreateAt(time());
        $manager->persist($event2);

        $event3 = new Event();
        $event3->setName('Встреча три');
        $event3->setCreator(2);
        $event3->setInvited(1);
        $event3->setStartAt(time() + 600);
        $event3->setEndAt(time() + 4200);
        $event3->setCreateAt(time());
        $manager->persist($event3);

        $manager->flush();
    }
}