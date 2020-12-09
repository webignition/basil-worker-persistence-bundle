<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Source;
use webignition\BasilWorker\PersistenceBundle\Services\Factory\SourceFactory;
use webignition\BasilWorker\PersistenceBundle\Services\SourceStore;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class SourceStoreTest extends AbstractFunctionalTest
{
    private SourceStore $sourceStore;
    private SourceFactory $sourceFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $sourceStore = $this->container->get(SourceStore::class);
        self::assertInstanceOf(SourceStore::class, $sourceStore);
        if ($sourceStore instanceof SourceStore) {
            $this->sourceStore = $sourceStore;
        }

        $sourceFactory = $this->container->get(\webignition\BasilWorker\PersistenceBundle\Services\Factory\SourceFactory::class);
        self::assertInstanceOf(\webignition\BasilWorker\PersistenceBundle\Services\Factory\SourceFactory::class, $sourceFactory);
        if ($sourceFactory instanceof \webignition\BasilWorker\PersistenceBundle\Services\Factory\SourceFactory) {
            $this->sourceFactory = $sourceFactory;
        }
    }

    public function testHasAny()
    {
        self::assertFalse($this->sourceStore->hasAny());

        $this->sourceFactory->create(Source::TYPE_TEST, 'Test/test.yml');

        self::assertTrue($this->sourceStore->hasAny());
    }

    /**
     * @dataProvider findAllPathsDataProvider
     *
     * @param Source[] $sources
     * @param string[] $expectedPaths
     */
    public function testFindAllPaths(array $sources, array $expectedPaths)
    {
        foreach ($sources as $source) {
            if ($source instanceof Source) {
                $this->entityManager->persist($source);
                $this->entityManager->flush();
            }
        }

        self::assertSame($expectedPaths, $this->sourceStore->findAllPaths());
    }

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
