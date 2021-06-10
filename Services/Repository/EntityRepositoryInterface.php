<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template T
 * @extends ObjectRepository<T>
 */
interface EntityRepositoryInterface extends ObjectRepository
{
    /**
     * @param mixed[]       $criteria
     * @param null|string[] $orderBy
     *
     * @return null|T
     */
    public function findOneBy(array $criteria, ?array $orderBy = null);

    /**
     * @param mixed[] $criteria
     */
    public function count(array $criteria): int;

    /**
     * @param string $indexBy the index for the from
     */
    public function createQueryBuilder(string $alias, $indexBy = null): QueryBuilder;
}
