<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set\Tests\Entity;

class Foo
{
    /** @var int|null */
    private $id = null;

    /** @var string */
    private $title;

    /**
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Foo
    {
        $this->title = $title;

        return $this;
    }
}
