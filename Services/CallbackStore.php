<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;

class CallbackStore
{
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(CallbackEntity::class);
    }

    public function get(int $id): ?CallbackEntity
    {
        return $this->repository->find($id);
    }

    public function getFinishedCount(): int
    {
        return count($this->repository->findBy([
            'state' => [
                CallbackInterface::STATE_FAILED,
                CallbackInterface::STATE_COMPLETE,
            ],
        ]));
    }

    public function getCompileFailureTypeCount(): int
    {
        return count($this->repository->findBy([
            'type' => [
                CallbackInterface::TYPE_COMPILE_FAILURE,
            ],
        ]));
    }

    public function getJobTimeoutTypeCount(): int
    {
        return count($this->repository->findBy([
            'type' => [
                CallbackInterface::TYPE_JOB_TIMEOUT,
            ],
        ]));
    }
}
