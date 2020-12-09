<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Persister;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;

class JobPersister extends AbstractObjectPersister
{
    public function persist(Job $job): Job
    {
        $this->doPersist($job);

        return $job;
    }
}
