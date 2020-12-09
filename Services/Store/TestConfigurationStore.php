<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Store;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use webignition\BasilWorker\PersistenceBundle\Entity\TestConfiguration;
use webignition\BasilWorker\PersistenceBundle\Services\EntityPersister;

class TestConfigurationStore
{
    private EntityPersister $persister;
    private ObjectRepository $repository;

    public function __construct(EntityPersister $persister, EntityManagerInterface $entityManager)
    {
        $this->persister = $persister;
        $this->repository = $entityManager->getRepository(TestConfiguration::class);
    }

    public function get(TestConfiguration $testConfiguration): TestConfiguration
    {
        return $this->find($testConfiguration->getBrowser(), $testConfiguration->getUrl());
    }

    private function find(string $browser, string $url): TestConfiguration
    {
        $testConfiguration = $this->repository->findOneBy([
            'browser' => $browser,
            'url' => $url,
        ]);

        if (!$testConfiguration instanceof TestConfiguration) {
            $testConfiguration = TestConfiguration::create($browser, $url);
            $this->persister->persist($testConfiguration);
        }

        return $testConfiguration;
    }
}
