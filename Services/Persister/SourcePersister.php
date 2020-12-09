<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Persister;

use webignition\BasilWorker\PersistenceBundle\Entity\Source;

class SourcePersister extends AbstractObjectPersister
{
    public function persist(Source $source): Source
    {
        $this->doPersist($source);

        return $source;
    }
}
