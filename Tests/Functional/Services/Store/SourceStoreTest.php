<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Entity\Source;
use webignition\BasilWorker\PersistenceBundle\Services\Factory\SourceFactory;
use webignition\BasilWorker\PersistenceBundle\Services\Store\SourceStore;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class SourceStoreTest extends AbstractFunctionalTest
{
    private SourceStore $store;
    private SourceFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $store = $this->container->get(SourceStore::class);
        self::assertInstanceOf(SourceStore::class, $store);
        if ($store instanceof SourceStore) {
            $this->store = $store;
        }

        $factory = $this->container->get(SourceFactory::class);
        self::assertInstanceOf(SourceFactory::class, $factory);
        if ($factory instanceof SourceFactory) {
            $this->factory = $factory;
        }
    }

    public function testHasAny(): void
    {
        self::assertFalse($this->store->hasAny());

        $this->factory->create(Source::TYPE_TEST, 'Test/test.yml');

        self::assertTrue($this->store->hasAny());
    }

    /**
     * @dataProvider findAllPathsDataProvider
     *
     * @param Source[] $sources
     * @param string[] $expectedPaths
     */
    public function testFindAllPaths(array $sources, array $expectedPaths): void
    {
        foreach ($sources as $source) {
            if ($source instanceof Source) {
                $this->entityManager->persist($source);
                $this->entityManager->flush();
            }
        }

        self::assertSame($expectedPaths, $this->store->findAllPaths());
    }

    /**
     * @return array[]
     */
    public function findAllPathsDataProvider(): array
    {
        return [
            'no sources' => [
                'sources' => [],
                'expectedPaths' => [],
            ],
            'has sources' => [
                'sources' => [
                    Source::create(Source::TYPE_TEST, 'Test/test1.yml'),
                    Source::create(Source::TYPE_TEST, 'Test/test2.yml'),
                    Source::create(Source::TYPE_TEST, 'Test/test3.yml'),
                ],
                'expectedPaths' => [
                    'Test/test1.yml',
                    'Test/test2.yml',
                    'Test/test3.yml',
                ],
            ],
        ];
    }
}
