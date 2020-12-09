<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Persister;

use webignition\BasilWorker\PersistenceBundle\Entity\Source;
use webignition\BasilWorker\PersistenceBundle\Services\Persister\SourcePersister;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class SourcePersisterTest extends AbstractFunctionalTest
{
    private SourcePersister $sourcePersister;

    protected function setUp(): void
    {
        parent::setUp();

        $callbackPersister = $this->container->get(SourcePersister::class);
        self::assertInstanceOf(SourcePersister::class, $callbackPersister);
        if ($callbackPersister instanceof SourcePersister) {
            $this->sourcePersister = $callbackPersister;
        }
    }

    public function testPersist()
    {
        $source = Source::create(Source::TYPE_TEST, 'Test/test.yml');

        self::assertNull($source->getId());
        $this->sourcePersister->persist($source);
        self::assertIsInt($source->getId());
    }
}
