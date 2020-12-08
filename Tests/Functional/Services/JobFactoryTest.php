<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Services\JobFactory;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class JobFactoryTest extends AbstractFunctionalTest
{
    private JobFactory $jobFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $jobFactory = $this->container->get(JobFactory::class);
        self::assertInstanceOf(JobFactory::class, $jobFactory);
        if ($jobFactory instanceof JobFactory) {
            $this->jobFactory = $jobFactory;
        }
    }

    public function testCreate()
    {
        $label = 'label content';
        $callbackUrl = 'http://example.com';
        $maximumDurationInSeconds = 600;

        $job = $this->jobFactory->create($label, $callbackUrl, $maximumDurationInSeconds);

        self::assertSame(Job::ID, $job->getId());
        self::assertSame($label, $job->getLabel());
        self::assertSame($callbackUrl, $job->getCallbackUrl());
        self::assertSame($maximumDurationInSeconds, $job->getMaximumDurationInSeconds());
    }
}
