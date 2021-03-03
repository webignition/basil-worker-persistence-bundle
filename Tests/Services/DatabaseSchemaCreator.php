<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class DatabaseSchemaCreator
{
    /**
     * @param array<class-string> $entityClasses
     */
    public function __construct(private EntityManagerInterface $entityManager, private array $entityClasses)
    {
    }

    public function create(): void
    {
        $tool = new SchemaTool($this->entityManager);

        $classMetadataCollection = [];
        foreach ($this->entityClasses as $entityClass) {
            $classMetadataCollection[] = $this->entityManager->getClassMetadata($entityClass);
        }

        $tool->createSchema($classMetadataCollection);
    }
}
