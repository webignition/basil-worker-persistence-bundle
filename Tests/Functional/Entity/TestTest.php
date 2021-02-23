<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Entity;

use webignition\BasilWorker\PersistenceBundle\Entity\Test;
use webignition\BasilWorker\PersistenceBundle\Entity\TestConfiguration;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class TestTest extends AbstractFunctionalTest
{
    public function testEntityMapping(): void
    {
        $repository = $this->entityManager->getRepository(Test::class);
        self::assertCount(0, $repository->findAll());

        $configuration = TestConfiguration::create('chrome', 'http://example.com');
        $this->entityManager->persist($configuration);
        $this->entityManager->flush();

        $test = Test::create($configuration, '/app/source/Test/test.yml', '/app/tests/GeneratedTest.php', 1, 1);
        $this->entityManager->persist($test);
        $this->entityManager->flush();

        self::assertCount(1, $repository->findAll());
    }
}
