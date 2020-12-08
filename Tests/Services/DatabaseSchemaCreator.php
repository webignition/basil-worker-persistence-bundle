<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Services;

use Doctrine\ORM\EntityManagerInterface;

class DatabaseSchemaCreator
{
    private EntityManagerInterface $entityManager;

    /**
     * @var array<class-string>
     */
    private array $entityClasses;

    /**
     * @param EntityManagerInterface $entityManager
     * @param array<class-string> $entityClasses
     */
    public function __construct(EntityManagerInterface $entityManager, array $entityClasses)
    {
        $this->entityManager = $entityManager;
        $this->entityClasses = $entityClasses;
    }

    public function create(): void
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);

        $classMetadataCollection = [];
        foreach ($this->entityClasses as $entityClass) {
            $classMetadataCollection[] = $this->entityManager->getClassMetadata($entityClass);
        }

//        $classes = array(
//            $this->entityManager->getClassMetadata(Job::class),
//        );
        $tool->createSchema($classMetadataCollection);
    }

//    protected function setUp(): void
//    {
//        parent::setUp();
//
//        /* @var \Doctrine\ORM\EntityManager $entityManager */
//        $entityManager = $this->container->get(EntityManagerInterface::class);
//
//        $tool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
//        $classes = array(
//            $entityManager->getClassMetadata(Job::class),
//        );
//        $tool->createSchema($classes);
//
//
//
////        var_dump($entityManager->getConnection()->getConfiguration());
//        var_dump($entityManager->getRepository(Job::class)->count([]));
//
////        $foo = $entityManager->
//
//        $jobStore = $this->container->get(JobStore::class);
//        self::assertInstanceOf(JobStore::class, $jobStore);
//        if ($jobStore instanceof JobStore) {
//            $this->jobStore = $jobStore;
//        }
//
//        $job = Job::create('foo', 'bar', 19);
//
//        $jobStore->store($job);
//
//        var_dump($entityManager->getRepository(Job::class)->count([]));
//    }
}
