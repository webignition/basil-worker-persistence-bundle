<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Entity\Source;
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
     * @param Source::TYPE_*|null $type
     *
     * @return string[]
     */
    public function findAllPaths(?string $type = null): array
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('Source')
            ->select('Source.path')
        ;

        if (is_string($type)) {
            $queryBuilder
                ->where('Source.type = :type')
                ->setParameter('type', $type)
            ;
        }

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
