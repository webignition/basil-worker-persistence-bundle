<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template T
 * @implements EntityRepositoryInterface<T>
 */
abstract class AbstractEntityRepository implements EntityRepositoryInterface
{
    /**
     * @var ObjectRepository<T>
     */
    private ObjectRepository $repository;

    /**
     * @param class-string<T> $className
     */
    public function __construct(private EntityManagerInterface $entityManager, string $className)
    {
        $this->repository = $entityManager->getRepository($className);
    }

    public function createQueryBuilder(string $alias, $indexBy = null): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()
            ->select($alias)
            ->from($this->getClassName(), $alias, $indexBy);
    }

    /**
     * @param mixed $id
     *
     * @return T|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param mixed[]       $criteria
     * @param string[]|null $orderBy
     * @param int|null      $limit
     * @param int|null      $offset
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param mixed[]       $criteria
     * @param string[]|null $orderBy
     *
     * @return T|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null)
    {
        $entities = $this->repository->findBy($criteria, $orderBy, 1, 0);
        $entity = $entities[0] ?? null;

        return is_object($entity) ? $entity : null;
    }

    public function getClassName(): string
    {
        return $this->repository->getClassName();
    }

    /**
     * @param mixed[] $criteria
     */
    public function count(array $criteria): int
    {
        return $this->entityManager->getUnitOfWork()->getEntityPersister($this->getClassName())->count($criteria);
    }
}
