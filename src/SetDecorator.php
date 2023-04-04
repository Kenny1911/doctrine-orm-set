<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;

abstract class SetDecorator implements Set
{
    /** @var Set */
    protected $inner;

    public function __construct(Set $inner)
    {
        $this->inner = $inner;
    }

    public function getSql(): string
    {
        return $this->inner->getSql();
    }

    public function getParameters(): ArrayCollection
    {
        return $this->inner->getParameters();
    }

    public function getQuery(): AbstractQuery
    {
        return $this->inner->getQuery();
    }
}