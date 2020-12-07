<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\Job;

class JobStore
{
    private EntityManagerInterface $entityManager;
    private Job $job;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function has(): bool
    {
        return $this->fetch() instanceof Job;
    }

    public function get(): Job
    {
        $job = $this->fetch();
        if ($job instanceof Job) {
            $this->job = $job;
        }

        return $this->job;
    }

    public function store(Job $job): Job
    {
        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $job;
    }

    private function fetch(): ?Job
    {
        $job = $this->entityManager->find(Job::class, Job::ID);

        return $job instanceof Job ? $job : null;
    }
}
