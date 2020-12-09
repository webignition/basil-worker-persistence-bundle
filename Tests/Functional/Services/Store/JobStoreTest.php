<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Services\Store\JobStore;
use webignition\BasilWorker\PersistenceBundle\Services\Persister\JobPersister;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class JobStoreTest extends AbstractFunctionalTest
{
    private JobStore $jobStore;
    private JobPersister $jobPersister;

    protected function setUp(): void
    {
        parent::setUp();

        $jobStore = $this->container->get(JobStore::class);
        self::assertInstanceOf(JobStore::class, $jobStore);
        if ($jobStore instanceof JobStore) {
            $this->jobStore = $jobStore;
        }

        $jobPersister = $this->container->get(JobPersister::class);
        self::assertInstanceOf(JobPersister::class, $jobPersister);
        if ($jobPersister instanceof JobPersister) {
            $this->jobPersister = $jobPersister;
        }
    }

    public function testHas()
    {
        self::assertFalse($this->jobStore->has());

        $this->jobPersister->persist(Job::create('label content', 'http://example.com/callback', 600));
        self::assertTrue($this->jobStore->has());
    }

    public function testGet()
    {
        $job = Job::create('label content', 'http://example.com/callback', 600);
        $this->jobPersister->persist($job);

        self::assertSame($this->jobStore->get(), $job);
    }
}
