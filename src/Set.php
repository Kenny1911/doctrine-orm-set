<?php

declare(strict_types=1);

namespace Kenny1911\Doctrine\Set;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Parameter;

interface Set
{
    public function getSql(): string;

    /**
     * @return ArrayCollection<int, Parameter>
     */
    public function getParameters(): ArrayCollection;

    public function getQuery(): AbstractQuery;
}
