<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;

class CallbackFactory
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param CallbackInterface::TYPE_* $type
     * @param array<mixed> $payload
     */
    public function create(string $type, array $payload): CallbackInterface
    {
        $callback = CallbackEntity::create($type, $payload);

        $this->entityManager->persist($callback);
        $this->entityManager->flush();

        return $callback;
    }
}
