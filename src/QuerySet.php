<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use LogicException;

final class QuerySet implements Set
{
    /** @var AbstractQuery */
    private $query;

    public function __construct(AbstractQuery $query)
    {
        $this->query = $query;
    }

    public function getSql(): string
    {
        $sql = $this->query->getSQL();

        if (is_array($sql)) {
            if (1 !== count($sql)) {
                throw new LogicException('Expected 1 sql query.');
            }

            $sql = reset($sql);
        }

        return $sql;
    }

    public function getParameters(): ArrayCollection
    {
        return $this->query->getParameters();
    }

    public function getQuery(): AbstractQuery
    {
        return $this->query;
    }
}
