<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set;

use Doctrine\ORM\Query\ResultSetMapping;
use Kenny1911\Doctrine\Set\Util\QueryCloner;

final class CountSet extends SetDecorator
{
    public function __construct(Set $set)
    {
        // Change Sql
        $sql = sprintf('SELECT COUNT(*) as cnt FROM (%s) AS records', $set->getSql());

        // Set ResultSetMapping
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('cnt', 'cnt', 'integer');

        $query = QueryCloner::create($set->getQuery())->withSQL($sql)->withResultSetMapping($rsm)->getQuery();

        parent::__construct(new QuerySet($query));
    }
}
