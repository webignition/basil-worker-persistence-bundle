<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\EntityInterface;

class EntityPersister
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function persist(EntityInterface $entity): EntityInterface
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }
}
