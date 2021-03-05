<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use webignition\BasilWorker\PersistenceBundle\Entity\Test;

/**
 * @extends AbstractEntityRepository<Test>
 */
class TestRepository extends AbstractEntityRepository
{
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

    public function findMaxPosition(): ?int
    {
        $test = $this->findOneBy([], [
            'position' => 'DESC',
        ]);

        return $test instanceof Test
            ? $test->getPosition()
            : null;
    }

    public function findNextAwaiting(): ?Test
    {
        $test = $this->findOneBy(
            [
                'state' => Test::STATE_AWAITING,
            ],
            [
                'position' => 'ASC',
            ]
        );

        return $test instanceof Test ? $test : null;
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

        try {
            $idValue = $query->getSingleResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

            return ctype_digit($idValue)
                ? (int) $idValue
                : null;
        } catch (NoResultException | NonUniqueResultException) {
        }

        return null;
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
}
