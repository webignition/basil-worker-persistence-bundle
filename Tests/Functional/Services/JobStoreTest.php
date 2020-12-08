<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Services\JobStore;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class JobStoreTest extends AbstractFunctionalTest
{
    private JobStore $jobStore;

    protected function setUp(): void
    {
        parent::setUp();

        $jobStore = $this->container->get(JobStore::class);
        self::assertInstanceOf(JobStore::class, $jobStore);
        if ($jobStore instanceof JobStore) {
            $this->jobStore = $jobStore;
        }
    }

    public function testHas()
    {
        self::assertFalse($this->jobStore->has());

        $this->jobStore->store(Job::create('label content', 'http://example.com/callback', 600));
        self::assertTrue($this->jobStore->has());
    }

    public function testGet()
    {
        $job = Job::create('label content', 'http://example.com/callback', 600);
        $this->jobStore->store($job);

        self::assertSame($this->jobStore->get(), $job);
    }
}
