<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use webignition\BasilWorker\PersistenceBundle\Entity\Test;

/**
 * @extends AbstractEntityRepository<Test>
 */
class TestRepository extends AbstractEntityRepository
{
    public const DEFAULT_MAX_POSITION = 0;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Test::class);
    }

    /**
     * @return Test[]
     */
    public function findAll(): array
    {
        return $this->findBy([], [
            'position' => 'ASC',
        ]);
    }

    public function findMaxPosition(): int
    {
        $queryBuilder = $this->createQueryBuilder('Test');
        $queryBuilder
            ->select('Test.position')
            ->orderBy('Test.position', 'DESC')
            ->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $value = $this->getSingleIntegerResult($query);

        return is_int($value) ? $value : self::DEFAULT_MAX_POSITION;
    }

    public function findNextAwaitingId(): ?int
    {
        $queryBuilder = $this->createQueryBuilder('Test');

        $queryBuilder
            ->select('Test.id')
            ->where('Test.state = :State')
            ->orderBy('Test.position', 'ASC')
            ->setMaxResults(1)
            ->setParameter('State', Test::STATE_AWAITING);

        $query = $queryBuilder->getQuery();

        return $this->getSingleIntegerResult($query);
    }

    /**
     * @return Test[]
     */
    public function findAllAwaiting(): array
    {
        return $this->findBy([
            'state' => Test::STATE_AWAITING,
        ]);
    }

    /**
     * @return Test[]
     */
    public function findAllUnfinished(): array
    {
        return $this->findBy([
            'state' => Test::UNFINISHED_STATES,
        ]);
    }

    /**
     * @return string[]
     */
    public function findAllSources(): array
    {
        $queryBuilder = $this->createQueryBuilder('Test');
        $queryBuilder
            ->select('Test.source');

        $query = $queryBuilder->getQuery();

        $result = $query->getArrayResult();

        $sources = [];
        foreach ($result as $item) {
            if (is_array($item)) {
                $sources[] = (string) ($item['source'] ?? null);
            }
        }

        return $sources;
    }

    private function getSingleIntegerResult(Query $query): ?int
    {
        try {
            $value = $query->getSingleResult($query::HYDRATE_SINGLE_SCALAR);
            if (ctype_digit($value)) {
                $value = (int) $value;
            }

            if (is_int($value)) {
                return $value;
            }
        } catch (NoResultException | NonUniqueResultException) {
        }

        return null;
    }
}
