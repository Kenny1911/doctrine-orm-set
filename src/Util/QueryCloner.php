<?php

namespace Kenny1911\Doctrine\Set\Util;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Query\ResultSetMapping;

final class QueryCloner
{
    /** @var AbstractQuery */
    private $query;

    /**
     * @param AbstractQuery $query
     */
    public function __construct(AbstractQuery $query)
    {
        $this->query = $query;
    }

    public static function create(AbstractQuery $query): QueryCloner
    {
        return new QueryCloner($query);
    }

    public function withSQL(string $sql): QueryCloner
    {
        $query = $this->getQuery();
        $query->setSQL($sql);

        return new QueryCloner($query);
    }

    /**
     * @param ArrayCollection|array $parameters
     * @psalm-param ArrayCollection<int, Parameter>|mixed[] $parameters
     */
    public function withParameters($parameters): QueryCloner
    {
        $query = $this->getQuery();
        $query->setParameters($parameters);

        return new QueryCloner($query);
    }

    public function withResultSetMapping(ResultSetMapping $rsm): QueryCloner
    {
        $query = $this->getQuery();
        $query->setResultSetMapping($rsm);

        return new QueryCloner($query);
    }

    public function getQuery(): NativeQuery
    {
        $query = new NativeQuery($this->query->getEntityManager());
        $query->setSQL($this->query->getSQL());
        $query->setResultSetMapping(QueryExtractor::getResultSetMapping($this->query));
        $query->setParameters($this->query->getParameters());

        return $query;
    }
}