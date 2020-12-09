<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Services\EntityPersister;
use webignition\BasilWorker\PersistenceBundle\Services\Store\JobStore;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class JobStoreTest extends AbstractFunctionalTest
{
    private JobStore $jobStore;
    private EntityPersister $persister;

    protected function setUp(): void
    {
        parent::setUp();

        $jobStore = $this->container->get(JobStore::class);
        self::assertInstanceOf(JobStore::class, $jobStore);
        if ($jobStore instanceof JobStore) {
            $this->jobStore = $jobStore;
        }

        $persister = $this->container->get(EntityPersister::class);
        self::assertInstanceOf(EntityPersister::class, $persister);
        if ($persister instanceof EntityPersister) {
            $this->persister = $persister;
        }
    }

    public function testHas()
    {
        self::assertFalse($this->jobStore->has());

        $this->persister->persist(Job::create('label content', 'http://example.com/callback', 600));
        self::assertTrue($this->jobStore->has());
    }

    public function testGet()
    {
        $job = Job::create('label content', 'http://example.com/callback', 600);
        $this->persister->persist($job);

        self::assertSame($this->jobStore->get(), $job);
    }
}
