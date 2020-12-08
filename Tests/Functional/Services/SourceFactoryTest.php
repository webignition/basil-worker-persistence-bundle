<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Source;
use webignition\BasilWorker\PersistenceBundle\Services\SourceFactory;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class SourceFactoryTest extends AbstractFunctionalTest
{
    private SourceFactory $sourceFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $sourceFactory = $this->container->get(SourceFactory::class);
        self::assertInstanceOf(SourceFactory::class, $sourceFactory);
        if ($sourceFactory instanceof SourceFactory) {
            $this->sourceFactory = $sourceFactory;
        }
    }

    public function testCreate()
    {
        $type = Source::TYPE_TEST;
        $path = 'Test/test.yml';
        $source = $this->sourceFactory->create($type, $path);

        self::assertNotNull($source->getId());
        self::assertSame($type, $source->getType());
        self::assertSame($path, $source->getPath());
    }
}
