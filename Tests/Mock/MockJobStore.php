<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Mock;

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Services\JobStore;

class MockJobStore
{
    /**
     * @var JobStore|MockInterface
     */
    private JobStore $jobStore;

    public function __construct()
    {
        $this->jobStore = \Mockery::mock(JobStore::class);
    }

    public function getMock(): JobStore
    {
        return $this->jobStore;
    }

    public function withStoreCall(Job $job): self
    {
        $this->jobStore
            ->shouldReceive('store')
            ->withArgs(function (Job $passedJob) use ($job) {
                TestCase::assertSame($job->getLabel(), $passedJob->getLabel());
                TestCase::assertSame($job->getCallbackUrl(), $passedJob->getCallbackUrl());
                TestCase::assertSame($job->getMaximumDurationInSeconds(), $passedJob->getMaximumDurationInSeconds());

                return true;
            });


        return $this;
    }
}
