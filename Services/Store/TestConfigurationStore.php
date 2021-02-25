<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Entity\TestConfiguration;
use webignition\BasilWorker\PersistenceBundle\Services\Factory\TestConfigurationFactory;
use webignition\BasilWorker\PersistenceBundle\Services\Repository\TestConfigurationRepository;

class TestConfigurationStore
{
    public function __construct(
        private TestConfigurationRepository $repository,
        private TestConfigurationFactory $factory
    ) {
    }

    public function get(TestConfiguration $testConfiguration): TestConfiguration
    {
        $existingConfiguration = $this->repository->findOneByConfiguration($testConfiguration);
        if ($existingConfiguration instanceof TestConfiguration) {
            return $existingConfiguration;
        }

        return $this->factory->create(
            $testConfiguration->getBrowser(),
            $testConfiguration->getUrl()
        );
    }
}
