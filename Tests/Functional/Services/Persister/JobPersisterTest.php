<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Persister;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Services\JobStore;
use webignition\BasilWorker\PersistenceBundle\Services\Persister\JobPersister;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class JobPersisterTest extends AbstractFunctionalTest
{
    private JobPersister $jobPersister;
    private JobStore $jobStore;

    protected function setUp(): void
    {
        parent::setUp();

        $jobPersister = $this->container->get(JobPersister::class);
        self::assertInstanceOf(JobPersister::class, $jobPersister);
        if ($jobPersister instanceof JobPersister) {
            $this->jobPersister = $jobPersister;
        }

        $jobStore = $this->container->get(JobStore::class);
        self::assertInstanceOf(JobStore::class, $jobStore);
        if ($jobStore instanceof JobStore) {
            $this->jobStore = $jobStore;
        }
    }

    public function testPersist()
    {
        $job = Job::create('label content', 'http://example.com/callback', 600);

        $this->assertFalse($this->jobStore->has());
        $this->jobPersister->persist($job);
        $this->assertTrue($this->jobStore->has());
    }
}
