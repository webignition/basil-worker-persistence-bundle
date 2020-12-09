<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Store;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ObjectRepository;
use webignition\BasilWorker\PersistenceBundle\Entity\Source;

class SourceStore
{
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Source::class);
    }

    public function hasAny(): bool
    {
        $queryBuilder = $this->repository->createQueryBuilder('Source');
        $queryBuilder
            ->select('COUNT(Source.id)')
            ->setMaxResults(1);

        $query = $queryBuilder->getQuery();

        try {
            $result = $query->getSingleScalarResult();

            return (int) $result > 0;
        } catch (NoResultException | NonUniqueResultException $e) {
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function findAllPaths(): array
    {
        $queryBuilder = $this->repository->createQueryBuilder('Source');
        $queryBuilder
            ->select('Source.path');

        $query = $queryBuilder->getQuery();
        $result = $query->getArrayResult();

        $paths = [];
        foreach ($result as $item) {
            if (is_array($item)) {
                $paths[] = (string) ($item['path'] ?? null);
            }
        }

        return $paths;
    }
}
