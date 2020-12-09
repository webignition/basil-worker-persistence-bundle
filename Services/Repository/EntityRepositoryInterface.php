<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Repository;

use Doctrine\Persistence\ObjectRepository;

/**
 * @template T
 * @extends ObjectRepository<T>
 */
interface EntityRepositoryInterface extends ObjectRepository
{
    /**
     * @param mixed[]       $criteria
     * @param string[]|null $orderBy
     *
     * @return T|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null);

    /**
     * @param mixed[] $criteria
     */
    public function count(array $criteria): int;
}
