<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\Test;
use webignition\BasilWorker\PersistenceBundle\Entity\TestConfiguration;
use webignition\BasilWorker\PersistenceBundle\Services\EntityPersister;
use webignition\BasilWorker\PersistenceBundle\Services\Repository\TestRepository;
use webignition\BasilWorker\PersistenceBundle\Services\Store\TestConfigurationStore;

class TestFactory extends AbstractFactory
{
    private TestRepository $repository;
    private TestConfigurationStore $configurationStore;

    public function __construct(
        EntityPersister $persister,
        TestRepository $repository,
        TestConfigurationStore $configurationStore
    ) {
        parent::__construct($persister);

        $this->repository = $repository;
        $this->configurationStore = $configurationStore;
    }

    public function create(
        TestConfiguration $configuration,
        string $source,
        string $target,
        int $stepCount
    ): Test {
        $test = Test::create(
            $this->configurationStore->get($configuration),
            $source,
            $target,
            $stepCount,
            $this->findNextPosition()
        );

        $this->persist($test);

        return $test;
    }

    private function findNextPosition(): int
    {
        $maxPosition = $this->repository->findMaxPosition();

        return null === $maxPosition
            ? 1
            : $maxPosition + 1;
    }
}
