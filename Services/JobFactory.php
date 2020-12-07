<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;

class JobFactory
{
    private JobStore $jobStore;

    public function __construct(JobStore $jobStore)
    {
        $this->jobStore = $jobStore;
    }

    public function create(string $label, string $callbackUrl, int $maximumDurationInSeconds): Job
    {
        return $this->jobStore->store(
            Job::create($label, $callbackUrl, $maximumDurationInSeconds)
        );
    }
}
