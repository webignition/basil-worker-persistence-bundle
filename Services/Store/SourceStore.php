<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Services\Repository\SourceRepository;

class SourceStore
{
    public function __construct(private SourceRepository $repository)
    {
    }

    public function hasAny(): bool
    {
        return $this->repository->count([]) > 0;
    }

    /**
     * @return string[]
     */
    public function findAllPaths(): array
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('Source')
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
