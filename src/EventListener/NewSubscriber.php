<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Events;
use App\Entity\Subscriber;
use App\Repository\SubscriberRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;

class NewSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $entity = $event->getObject();

        // is object is no a subscriber, skip
        if (!$entity instanceof Subscriber) {
            return;
        }

        if($entity->getUser() !== null) {
            return;
        }

        $user = $event->getObjectManager()->getRepository(User::class)->findOneBy(['email' => $entity->getEmail()]);

        if(!empty($user)) {
            $entity->setUser($user);
            $user->setSubscription($entity);
            $event->getObjectManager()->flush();
        }
    }
}