<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Persister;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractObjectPersister
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function doPersist(object $object): object
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();

        return $object;
    }
}
