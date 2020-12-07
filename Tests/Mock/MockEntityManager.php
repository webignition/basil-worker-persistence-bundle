<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Mock;

use Doctrine\ORM\EntityManagerInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use webignition\BasilWorker\PersistenceBundle\Entity\Job;

class MockEntityManager
{
    /**
     * @var EntityManagerInterface|MockInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct()
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
    }

    public function getMock(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function withFindCall(string $className, int $jobId, ?object $object): self
    {
        $this->entityManager
            ->shouldReceive('find')
            ->with($className, $jobId)
            ->andReturn($object);

        return $this;
    }

    public function withPersistCall(Job $job): self
    {
        $this->entityManager
            ->shouldReceive('persist')
            ->withArgs(function (Job $passedJob) use ($job) {
                TestCase::assertSame($job->getLabel(), $passedJob->getLabel());
                TestCase::assertSame($job->getCallbackUrl(), $passedJob->getCallbackUrl());
                TestCase::assertSame($job->getMaximumDurationInSeconds(), $passedJob->getMaximumDurationInSeconds());

                return true;
            });

        return $this;
    }

    public function withFlushCall(): self
    {
        $this->entityManager
            ->shouldReceive('flush');

        return $this;
    }
}
