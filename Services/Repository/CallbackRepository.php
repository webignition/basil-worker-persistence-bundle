<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;

/**
 * @extends \Doctrine\Persistence\ObjectRepository<CallbackEntity>
 */
class CallbackRepository implements ObjectRepository
{
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(CallbackEntity::class);
    }

    public function find($id): ?CallbackEntity
    {
        return $this->repository->find($id);
    }

    /**
     * @return CallbackEntity
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param mixed[] $criteria
     * @param string[]|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return CallbackEntity[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param mixed[] $criteria
     * @param string[]|null $orderBy
     *
     * @return CallbackEntity|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?CallbackEntity
    {
        $callbacks = $this->repository->findBy($criteria, $orderBy, 1, 0);
        $callback = $callbacks[0] ?? null;

        return $callback instanceof CallbackEntity ? $callback : null;
    }

    public function getClassName(): string
    {
        return $this->repository->getClassName();
    }

    /**
     * @param mixed[] $criteria
     *
     * @return int
     */
    public function count(array $criteria): int
    {
        return $this->entityManager->getUnitOfWork()->getEntityPersister($this->getClassName())->count($criteria);
    }
}
