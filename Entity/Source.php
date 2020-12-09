<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Source implements EntityInterface
{
    public const TYPE_TEST = 'test';
    public const TYPE_RESOURCE = 'resource';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @var Source::TYPE_*
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $type;

    /**
     * @ORM\Column(type="text")
     */
    private string $path;

    /**
     * @param Source::TYPE_* $type
     * @param string $path
     *
     * @return self
     */
    public static function create(string $type, string $path): self
    {
        $source = new Source();
        $source->type = $type;
        $source->path = $path;

        return $source;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
