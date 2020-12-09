<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Entity;

use webignition\BasilWorker\PersistenceBundle\Entity\TestConfiguration;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class TestConfigurationTest extends AbstractFunctionalTest
{
    public function testEntityMapping()
    {
        $repository = $this->entityManager->getRepository(TestConfiguration::class);
        self::assertCount(0, $repository->findAll());

        $configuration = TestConfiguration::create('chrome', 'http://example.com');

        $this->entityManager->persist($configuration);
        $this->entityManager->flush();

        self::assertCount(1, $repository->findAll());
    }
}
