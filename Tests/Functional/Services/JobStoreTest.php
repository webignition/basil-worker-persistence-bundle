<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services;

use Doctrine\ORM\EntityManagerInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Services\JobStore;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;
use webignition\BasilWorker\PersistenceBundle\Tests\Mock\MockEntityManager;
use webignition\ObjectReflector\ObjectReflector;

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

    /**
     * @dataProvider hasDataProvider
     */
    public function testHas(EntityManagerInterface $entityManager, bool $expectedHas)
    {
        ObjectReflector::setProperty(
            $this->jobStore,
            JobStore::class,
            'entityManager',
            $entityManager
        );

        self::assertSame($expectedHas, $this->jobStore->has());
    }

    public function hasDataProvider(): array
    {
        return [
            'not has, empty' => [
                'entityManager' => (new MockEntityManager())
                    ->withFindCall(Job::class, Job::ID, null)
                    ->getMock(),
                'expectedHas' => false,
            ],
            'not has, wrong type' => [
                'entityManager' => (new MockEntityManager())
                    ->withFindCall(Job::class, Job::ID, new \stdClass())
                    ->getMock(),
                'expectedHas' => false,
            ],
            'has' => [
                'entityManager' => (new MockEntityManager())
                    ->withFindCall(Job::class, Job::ID, \Mockery::mock(Job::class))
                    ->getMock(),
                'expectedHas' => true,
            ],
        ];
    }

    public function testGet()
    {
        $job = \Mockery::mock(Job::class);

        $entityManager = (new MockEntityManager())
            ->withFindCall(Job::class, Job::ID, $job)
            ->getMock();

        ObjectReflector::setProperty(
            $this->jobStore,
            JobStore::class,
            'entityManager',
            $entityManager
        );

        self::assertSame($this->jobStore->get(), $job);
    }

    public function testStore()
    {
        $job = \Mockery::mock(Job::class);

        $entityManager = (new MockEntityManager())
            ->withPersistCall($job)
            ->withFlushCall()
            ->getMock();

        ObjectReflector::setProperty(
            $this->jobStore,
            JobStore::class,
            'entityManager',
            $entityManager
        );

        $this->jobStore->store($job);
    }
}
