<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\EntityInterface;
use webignition\BasilWorker\PersistenceBundle\Services\EntityPersister;

abstract class AbstractFactory
{
    public function __construct(private EntityPersister $persister)
    {
    }

    protected function persist(EntityInterface $entity): EntityInterface
    {
        return $this->persister->persist($entity);
    }
}
