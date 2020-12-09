<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Repository;

use webignition\BasilWorker\PersistenceBundle\Entity\EntityInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\Test;
use webignition\BasilWorker\PersistenceBundle\Entity\TestConfiguration;
use webignition\BasilWorker\PersistenceBundle\Services\Repository\TestRepository;
use webignition\BasilWorker\PersistenceBundle\Services\Store\TestConfigurationStore;
use webignition\ObjectReflector\ObjectReflector;

/**
 * @extends AbstractEntityRepositoryTest<Test>
 */
class TestRepositoryTest extends AbstractEntityRepositoryTest
{
    private TestConfigurationStore $testConfigurationStore;

    protected function setUp(): void
    {
        parent::setUp();

        $testConfigurationStore = $this->container->get(TestConfigurationStore::class);
        self::assertInstanceOf(TestConfigurationStore::class, $testConfigurationStore);
        if ($testConfigurationStore instanceof TestConfigurationStore) {
            $this->testConfigurationStore = $testConfigurationStore;
        }
    }

    protected function getRepository(): ?TestRepository
    {
        $repository = $this->container->get(TestRepository::class);
        if ($repository instanceof TestRepository) {
            return $repository;
        }

        return null;
    }

    protected function persistEntity(EntityInterface $entity): void
    {
        if ($entity instanceof Test) {
            ObjectReflector::setProperty(
                $entity,
                Test::class,
                'configuration',
                $this->testConfigurationStore->get($entity->getConfiguration())
            );
        }

        parent::persistEntity($entity);
    }

    protected function createSingleEntity(): EntityInterface
    {
        return Test::create(
            TestConfiguration::create('chrome', 'http://example.com/'),
            '/app/source/Test/test.yml',
            '/app/tests/GeneratedTest.php',
            1,
            1
        );
    }

    protected function createEntityCollection(): array
    {
        return [
            Test::create(
                TestConfiguration::create('chrome', 'http://example.com/'),
                '/app/source/Test/test1.yml',
                '/app/tests/GeneratedTest1.php',
                4,
                1
            ),
            Test::create(
                TestConfiguration::create('firefox', 'http://example.com/'),
                '/app/source/Test/test1.yml',
                '/app/tests/GeneratedTest2.php',
                4,
                2
            ),
            Test::create(
                TestConfiguration::create('chrome', 'http://example.com/'),
                '/app/source/Test/test2.yml',
                '/app/tests/GeneratedTest3.php',
                7,
                3
            ),
        ];
    }

    public function findOneByDataProvider(): array
    {
        return [
            'source /app/source/Test/test1.yml' => [
                'criteria' => [
                    'source' => '/app/source/Test/test1.yml',
                ],
                'orderBy' => null,
                'expectedEntityIndex' => 0,
            ],
            'target /app/tests/GeneratedTest2.php' => [
                'criteria' => [
                    'target' => '/app/tests/GeneratedTest2.php',
                ],
                'orderBy' => null,
                'expectedEntityIndex' => 1,
            ],
            'step count 7' => [
                'criteria' => [
                    'stepCount' => 7,
                ],
                'orderBy' => null,
                'expectedEntityIndex' => 2,
            ],
        ];
    }

    public function countDataProvider(): array
    {
        return [
            'source /app/source/Test/test1.yml' => [
                'criteria' => [
                    'source' => '/app/source/Test/test1.yml',
                ],
                'expectedCount' => 2,
            ],
            'target /app/tests/GeneratedTest2.php' => [
                'criteria' => [
                    'target' => '/app/tests/GeneratedTest2.php',
                ],
                'expectedCount' => 1,
            ],
            'step count 7' => [
                'criteria' => [
                    'stepCount' => 7,
                ],
                'expectedCount' => 1,
            ],
        ];
    }
}
