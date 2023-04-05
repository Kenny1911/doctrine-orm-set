<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set;

use Doctrine\Common\Collections\ArrayCollection;
use Kenny1911\Doctrine\Set\Util\QueryCloner;
use LogicException;

abstract class CombineSet extends SetDecorator
{
    /**
     * @param array<Set> $sets
     */
    public function __construct(array $sets, string $operator, bool $wrap)
    {
        if (2 > count($sets)) {
            throw new LogicException('Count of union sets must be 2 or more.');
        }

        $sql = implode(
            ' '.$operator.' ',
            array_map(
                function (Set $set) {
                    return $set->getSql();
                },
                $sets
            )
        );

        if ($wrap) {
            $sql = '('.$sql.')';
        }

        $parameters = [];

        foreach ($sets as $set) {
            $parameters = array_merge($parameters, $set->getParameters()->toArray());
        }

        $firstSet = $sets[0];

        $query = QueryCloner::create($firstSet->getQuery())
            ->withSQL($sql)
            ->withParameters(new ArrayCollection($parameters))
            ->getQuery()
        ;

        parent::__construct(new QuerySet($query));
    }
}
