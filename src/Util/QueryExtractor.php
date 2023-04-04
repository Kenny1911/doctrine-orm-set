<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set\Util;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\ResultSetMapping;
use LogicException;
use ReflectionProperty;

final class QueryExtractor
{
    public static function getResultSetMapping(AbstractQuery $query): ResultSetMapping
    {
        $ref = new ReflectionProperty(AbstractQuery::class, '_resultSetMapping');
        $ref->setAccessible(true);

        $rsm = $ref->getValue($query);

        if ($rsm instanceof ResultSetMapping) {
            return $rsm;
        }

        throw new LogicException('Uninitialized result set mapping.');
    }

    /**
     * @throws Exception
     */
    public static function getPlatform(AbstractQuery $query): AbstractPlatform
    {
        $platform = $query->getEntityManager()->getConnection()->getDatabasePlatform();

        if ($platform instanceof AbstractPlatform) {
            return $platform;
        }

        throw new Exception('Uninitialized database platform.');
    }
}
