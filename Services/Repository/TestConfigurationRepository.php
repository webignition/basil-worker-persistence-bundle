<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Repository;

use Doctrine\ORM\EntityManagerInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\TestConfiguration;

/**
 * @extends AbstractEntityRepository<TestConfiguration>
 */
class TestConfigurationRepository extends AbstractEntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, TestConfiguration::class);
    }

    public function findOneByConfiguration(TestConfiguration $configuration): ?TestConfiguration
    {
        return $this->findOneBy([
            'browser' => $configuration->getBrowser(),
            'url' => $configuration->getUrl(),
        ]);
    }
}
