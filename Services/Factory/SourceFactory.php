<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\Source;
use webignition\BasilWorker\PersistenceBundle\Services\Persister\SourcePersister;

class SourceFactory
{
    private SourcePersister $sourcePersister;

    public function __construct(SourcePersister $sourcePersister)
    {
        $this->sourcePersister = $sourcePersister;
    }

    /**
     * @param Source::TYPE_* $type
     */
    public function create(string $type, string $path): Source
    {
        return $this->sourcePersister->persist(Source::create($type, $path));
    }
}
