<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\TestConfiguration;

class TestConfigurationFactory extends AbstractFactory
{
    public function create(string $browser, string $url): TestConfiguration
    {
        $configuration = TestConfiguration::create($browser, $url);

        $this->persist($configuration);

        return $configuration;
    }
}
