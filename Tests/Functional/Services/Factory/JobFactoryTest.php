<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Services\Factory\JobFactory;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class JobFactoryTest extends AbstractFunctionalTest
{
    private JobFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $factory = $this->container->get(JobFactory::class);
        self::assertInstanceOf(JobFactory::class, $factory);
        if ($factory instanceof JobFactory) {
            $this->factory = $factory;
        }
    }

    public function testCreate()
    {
        $label = 'label content';
        $callbackUrl = 'http://example.com';
        $maximumDurationInSeconds = 600;

        $job = $this->factory->create($label, $callbackUrl, $maximumDurationInSeconds);

        self::assertSame(Job::ID, $job->getId());
        self::assertSame($label, $job->getLabel());
        self::assertSame($callbackUrl, $job->getCallbackUrl());
        self::assertSame($maximumDurationInSeconds, $job->getMaximumDurationInSeconds());
    }
}
