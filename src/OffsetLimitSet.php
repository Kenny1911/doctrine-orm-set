<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set;

use Kenny1911\Doctrine\Set\Util\QueryCloner;
use Kenny1911\Doctrine\Set\Util\QueryExtractor;

final class OffsetLimitSet extends SetDecorator
{
    public function __construct(Set $set, ?int $limit = null, int $offset = 0)
    {
        $sql = sprintf('SELECT * FROM (%s) AS records', $set->getSql());

        $platform = QueryExtractor::getPlatform($set->getQuery());
        $sql = $platform->modifyLimitQuery($sql, $limit, $offset);

        $query = QueryCloner::create($set->getQuery())->withSQL($sql)->getQuery();

        parent::__construct(new QuerySet($query));
    }
}
