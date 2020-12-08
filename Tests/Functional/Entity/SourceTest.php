<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Entity;

use webignition\BasilWorker\PersistenceBundle\Entity\Source;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class SourceTest extends AbstractFunctionalTest
{
    public function testEntityMapping()
    {
        $repository = $this->entityManager->getRepository(Source::class);
        self::assertCount(0, $repository->findAll());

        $source = Source::create(Source::TYPE_TEST, 'Test/test.yml');

        $this->entityManager->persist($source);
        $this->entityManager->flush();

        self::assertCount(1, $repository->findAll());
    }
}
