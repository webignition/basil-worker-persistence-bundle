<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Repository;

use Doctrine\ORM\EntityManagerInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\Source;

/**
 * @extends AbstractEntityRepository<Source>
 */
class SourceRepository extends AbstractEntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Source::class);
    }
}
