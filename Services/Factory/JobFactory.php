<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;

class JobFactory extends AbstractFactory
{
    public function create(string $label, string $callbackUrl, int $maximumDurationInSeconds): Job
    {
        $job = Job::create($label, $callbackUrl, $maximumDurationInSeconds);

        $this->persist($job);

        return $job;
    }
}
