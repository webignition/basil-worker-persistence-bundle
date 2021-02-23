<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\Source;
use webignition\BasilWorker\PersistenceBundle\Services\Factory\SourceFactory;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class SourceFactoryTest extends AbstractFunctionalTest
{
    private SourceFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $factory = $this->container->get(SourceFactory::class);
        self::assertInstanceOf(SourceFactory::class, $factory);
        if ($factory instanceof SourceFactory) {
            $this->factory = $factory;
        }
    }

    public function testCreate(): void
    {
        $type = Source::TYPE_TEST;
        $path = 'Test/test.yml';
        $source = $this->factory->create($type, $path);

        self::assertNotNull($source->getId());
        self::assertSame($type, $source->getType());
        self::assertSame($path, $source->getPath());
    }
}
