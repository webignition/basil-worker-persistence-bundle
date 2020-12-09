<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\Source;

class SourceFactory extends AbstractFactory
{
    /**
     * @param Source::TYPE_* $type
     */
    public function create(string $type, string $path): Source
    {
        $source = Source::create($type, $path);

        $this->persist($source);

        return $source;
    }
}
