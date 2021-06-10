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
     * @param Source[]            $sources
     * @param Source::TYPE_*|null $type
     * @param string[]            $expectedPaths
     */
    public function testFindAllPaths(array $sources, ?string $type, array $expectedPaths): void
    {
        foreach ($sources as $source) {
            if ($source instanceof Source) {
                $this->entityManager->persist($source);
                $this->entityManager->flush();
            }
        }

        self::assertSame($expectedPaths, $this->store->findAllPaths($type));
    }

    /**
     * @return array[]
     */
    public function findAllPathsDataProvider(): array
    {
        return [
            'no sources' => [
                'sources' => [],
                'type' => null,
                'expectedPaths' => [],
            ],
            'test-only sources, type=test' => [
                'sources' => [
                    Source::create(Source::TYPE_TEST, 'Test/test1.yml'),
                    Source::create(Source::TYPE_TEST, 'Test/test2.yml'),
                ],
                'type' => Source::TYPE_TEST,
                'expectedPaths' => [
                    'Test/test1.yml',
                    'Test/test2.yml',
                ],
            ],
            'test-only sources, type=resource' => [
                'sources' => [
                    Source::create(Source::TYPE_TEST, 'Test/test1.yml'),
                ],
                'type' => Source::TYPE_RESOURCE,
                'expectedPaths' => [],
            ],
            'resource-only sources, type=resource' => [
                'sources' => [
                    Source::create(Source::TYPE_RESOURCE, 'Page/page1.yml'),
                    Source::create(Source::TYPE_RESOURCE, 'Page/page2.yml'),
                ],
                'type' => Source::TYPE_RESOURCE,
                'expectedPaths' => [
                    'Page/page1.yml',
                    'Page/page2.yml',
                ],
            ],
            'resource-only sources, type=test' => [
                'sources' => [
                    Source::create(Source::TYPE_RESOURCE, 'Page/page1.yml'),
                ],
                'type' => Source::TYPE_TEST,
                'expectedPaths' => [],
            ],
            'mixed-type sources, type=null' => [
                'sources' => [
                    Source::create(Source::TYPE_RESOURCE, 'Page/page1.yml'),
                    Source::create(Source::TYPE_TEST, 'Test/test1.yml'),
                    Source::create(Source::TYPE_RESOURCE, 'Page/page2.yml'),
                    Source::create(Source::TYPE_TEST, 'Test/test2.yml'),
                ],
                'type' => null,
                'expectedPaths' => [
                    'Page/page1.yml',
                    'Test/test1.yml',
                    'Page/page2.yml',
                    'Test/test2.yml',
                ],
            ],
            'mixed-type sources, type=test' => [
                'sources' => [
                    Source::create(Source::TYPE_RESOURCE, 'Page/page1.yml'),
                    Source::create(Source::TYPE_TEST, 'Test/test1.yml'),
                    Source::create(Source::TYPE_RESOURCE, 'Page/page2.yml'),
                    Source::create(Source::TYPE_TEST, 'Test/test2.yml'),
                ],
                'type' => Source::TYPE_TEST,
                'expectedPaths' => [
                    'Test/test1.yml',
                    'Test/test2.yml',
                ],
            ],
            'mixed-type sources, type=resource' => [
                'sources' => [
                    Source::create(Source::TYPE_RESOURCE, 'Page/page1.yml'),
                    Source::create(Source::TYPE_TEST, 'Test/test1.yml'),
                    Source::create(Source::TYPE_RESOURCE, 'Page/page2.yml'),
                    Source::create(Source::TYPE_TEST, 'Test/test2.yml'),
                ],
                'type' => Source::TYPE_RESOURCE,
                'expectedPaths' => [
                    'Page/page1.yml',
                    'Page/page2.yml',
                ],
            ],
        ];
    }
}
