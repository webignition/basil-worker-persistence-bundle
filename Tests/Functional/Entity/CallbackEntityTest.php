<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Entity;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class CallbackEntityTest extends AbstractFunctionalTest
{
    public function testEntityMapping(): void
    {
        $repository = $this->entityManager->getRepository(CallbackEntity::class);
        self::assertCount(0, $repository->findAll());

        $callback = CallbackEntity::create(CallbackEntity::TYPE_COMPILATION_FAILED, []);

        $this->entityManager->persist($callback);
        $this->entityManager->flush();

        self::assertCount(1, $repository->findAll());
    }
}
