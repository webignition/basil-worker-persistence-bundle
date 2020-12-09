<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Services\Persister\JobPersister;

class JobFactory
{
    private JobPersister $jobPersister;

    public function __construct(JobPersister $jobPersister)
    {
        $this->jobPersister = $jobPersister;
    }

    public function create(string $label, string $callbackUrl, int $maximumDurationInSeconds): Job
    {
        return $this->jobPersister->persist(
            Job::create($label, $callbackUrl, $maximumDurationInSeconds)
        );
    }
}
