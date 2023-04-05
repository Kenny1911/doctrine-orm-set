<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set\Tests;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Kenny1911\Doctrine\Set\CountSet;
use Kenny1911\Doctrine\Set\QuerySet;
use Kenny1911\Doctrine\Set\Tests\Entity\Foo;
use PHPUnit\Framework\TestCase;

final class CountSetTest extends TestCase
{
    /** @var CountSet */
    private $set;

    /**
     * @throws MissingMappingDriverImplementation
     * @throws Exception
     */
    protected function setUp(): void
    {
        $em = DB::init();
        DB::fill($em);

        $query = new NativeQuery($em);
        $query->setSQL('SELECT f.id FROM Foo f');

        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(Foo::class, 'f.id');

        $query->setResultSetMapping($rsm);

        $this->set = new CountSet(new QuerySet($query));
    }

    public function testGetSql(): void
    {
        $this->assertSame(
            'SELECT COUNT(*) as cnt FROM (SELECT f.id FROM Foo f) AS records',
            $this->set->getSql()
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testGetQuery(): void
    {
        $this->assertSame(10, $this->set->getQuery()->getSingleScalarResult());
    }
}
