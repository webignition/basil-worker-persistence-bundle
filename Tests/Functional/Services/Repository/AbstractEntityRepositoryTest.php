<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Repository;

use Doctrine\ORM\QueryBuilder;
use webignition\BasilWorker\PersistenceBundle\Entity\EntityInterface;
use webignition\BasilWorker\PersistenceBundle\Services\Repository\EntityRepositoryInterface;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

/**
 * @template T
 */
abstract class AbstractEntityRepositoryTest extends AbstractFunctionalTest
{
    /**
     * @var EntityRepositoryInterface<T>
     */
    protected EntityRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = $this->getRepository();
        if ($repository instanceof EntityRepositoryInterface) {
            $this->repository = $repository;
        }
    }

    /**
     * @return EntityRepositoryInterface<T>
     */
    abstract protected function getRepository(): ?EntityRepositoryInterface;
    abstract protected function createSingleEntity(): EntityInterface;

    /**
     * @return array[]
     */
    abstract protected function findOneByDataProvider(): array;

    /**
     * @return array[]
     */
    abstract protected function countDataProvider(): array;

    /**
     * @return EntityInterface[]
     */
    abstract protected function createEntityCollection(): array;

    public function testFind(): void
    {
        $this->assertNull($this->repository->find(0));

        $entity = $this->createSingleEntity();
        self::assertInstanceOf(EntityInterface::class, $entity);

        $this->persistEntity($entity);

        self::assertIsInt($entity->getId());
        self::assertSame($entity, $this->repository->find($entity->getId()));
    }

    public function testFindAll(): void
    {
        $this->assertSame([], $this->repository->findAll());

        $entities = $this->createEntityCollection();
        foreach ($entities as $entity) {
            $this->persistEntity($entity);
        }

        self::assertSame($entities, $this->repository->findAll());
    }

    /**
     * @dataProvider findOneByDataProvider
     *
     * @param mixed[] $criteria
     * @param mixed[] $orderBy
     */
    public function testFindOneBy(array $criteria, ?array $orderBy, ?int $expectedEntityIndex): void
    {
        $entities = $this->createEntityCollection();
        foreach ($entities as $entity) {
            $this->persistEntity($entity);
        }

        $entity = $this->repository->findOneBy($criteria, $orderBy);

        if (null === $expectedEntityIndex) {
            self::assertNull($entity);
        } else {
            self::assertSame($entities[$expectedEntityIndex], $entity);
        }
    }

    /**
     * @dataProvider countDataProvider
     *
     * @param mixed[] $criteria
     */
    public function testCount(array $criteria, int $expectedCount): void
    {
        $entities = $this->createEntityCollection();
        foreach ($entities as $entity) {
            $this->persistEntity($entity);
        }

        self::assertSame($expectedCount, $this->repository->count($criteria));
    }

    public function testCreateQueryBuilder(): void
    {
        $queryBuilder = $this->repository->createQueryBuilder('Foo');

        self::assertInstanceOf(QueryBuilder::class, $queryBuilder);
        self::assertSame($this->entityManager, $queryBuilder->getEntityManager());
    }

    protected function persistEntity(EntityInterface $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
