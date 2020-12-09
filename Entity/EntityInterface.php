<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Entity;

interface EntityInterface
{
    public function getId(): ?int;
}
