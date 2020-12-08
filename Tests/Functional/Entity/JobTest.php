<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Entity;

use webignition\BasilWorker\PersistenceBundle\Entity\Job;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class JobTest extends AbstractFunctionalTest
{
    public function testEntityMapping()
    {
        $repository = $this->entityManager->getRepository(Job::class);
        self::assertCount(0, $repository->findAll());

        $job = Job::create('label content', 'http://example.com/callback', 600);

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        self::assertCount(1, $repository->findAll());
    }
}
