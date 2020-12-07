<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Services\JobFactory;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;
use webignition\BasilWorker\PersistenceBundle\Tests\Mock\MockJobStore;
use webignition\ObjectReflector\ObjectReflector;

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

        $jobStore = (new MockJobStore())
            ->withStoreCall(Job::create($label, $callbackUrl, $maximumDurationInSeconds))
            ->getMock();

        ObjectReflector::setProperty(
            $this->jobFactory,
            JobFactory::class,
            'jobStore',
            $jobStore
        );

        $this->jobFactory->create($label, $callbackUrl, $maximumDurationInSeconds);
    }
}
