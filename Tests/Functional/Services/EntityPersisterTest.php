<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\EntityInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Entity\Source;
use webignition\BasilWorker\PersistenceBundle\Services\EntityPersister;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class EntityPersisterTest extends AbstractFunctionalTest
{
    private EntityPersister $entityPersister;

    protected function setUp(): void
    {
        parent::setUp();

        $entityPersister = $this->container->get(EntityPersister::class);
        self::assertInstanceOf(EntityPersister::class, $entityPersister);
        if ($entityPersister instanceof EntityPersister) {
            $this->entityPersister = $entityPersister;
        }
    }

    /**
     * @dataProvider persistDataProvider
     */
    public function testPersist(EntityInterface $entity)
    {
        $repository = $this->entityManager->getRepository(get_class($entity));
        self::assertCount(0, $repository->findAll());

        $this->entityPersister->persist($entity);
        self::assertCount(1, $repository->findAll());
    }

    public function persistDataProvider(): array
    {
        return [
            'callback' => [
                'entity' => CallbackEntity::create(CallbackInterface::TYPE_COMPILE_FAILURE, []),
            ],
            'job' => [
                'entity' => Job::create('label content', 'http://example.com/callback', 600),
            ],
            'source' => [
                'entity' => Source::create(Source::TYPE_TEST, 'Test/test.yml'),
            ],
        ];
    }
}
