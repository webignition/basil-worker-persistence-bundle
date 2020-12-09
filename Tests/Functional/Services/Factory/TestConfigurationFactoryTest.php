<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Services\Factory\TestConfigurationFactory;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class TestConfigurationFactoryTest extends AbstractFunctionalTest
{
    private TestConfigurationFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $factory = $this->container->get(TestConfigurationFactory::class);
        self::assertInstanceOf(TestConfigurationFactory::class, $factory);
        if ($factory instanceof TestConfigurationFactory) {
            $this->factory = $factory;
        }
    }

    public function testCreate()
    {
        $browser = 'chrome';
        $url = 'http://example.com';

        $testConfiguration = $this->factory->create($browser, $url);

        self::assertNotNull($testConfiguration->getId());
        self::assertSame($browser, $testConfiguration->getBrowser());
        self::assertSame($url, $testConfiguration->getUrl());
    }
}
