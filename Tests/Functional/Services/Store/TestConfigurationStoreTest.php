<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Entity\TestConfiguration;
use webignition\BasilWorker\PersistenceBundle\Services\Store\TestConfigurationStore;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class TestConfigurationStoreTest extends AbstractFunctionalTest
{
    private TestConfigurationStore $store;

    protected function setUp(): void
    {
        parent::setUp();

        $store = $this->container->get(TestConfigurationStore::class);
        self::assertInstanceOf(TestConfigurationStore::class, $store);
        if ($store instanceof TestConfigurationStore) {
            $this->store = $store;
        }
    }

    public function testGet()
    {
        $configuration = TestConfiguration::create('chrome', 'http://example.com');
        self::assertNull($configuration->getId());

        $retrievedConfiguration = $this->store->get($configuration);

        self::assertIsInt($retrievedConfiguration->getId());
        self::assertSame($configuration->getBrowser(), $retrievedConfiguration->getBrowser());
        self::assertSame($configuration->getUrl(), $retrievedConfiguration->getUrl());

        self::assertSame($retrievedConfiguration, $this->store->get($retrievedConfiguration));
    }
}
