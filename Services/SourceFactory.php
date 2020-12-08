<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\Source;

class SourceFactory
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Source::TYPE_* $type
     */
    public function create(string $type, string $path): Source
    {
        $source = Source::create($type, $path);

        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}
