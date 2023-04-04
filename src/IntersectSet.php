<?php

namespace Kenny1911\Doctrine\Set;

final class IntersectSet extends CombineSet
{
    /**
     * @param array<Set> $sets
     */
    public function __construct(array $sets)
    {
        parent::__construct($sets, 'INTERSECT');
    }
}