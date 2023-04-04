<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set;

final class ExceptSet extends CombineSet
{
    /**
     * @param array<Set> $sets
     */
    public function __construct(array $sets)
    {
        parent::__construct($sets, 'EXCEPT');
    }
}
