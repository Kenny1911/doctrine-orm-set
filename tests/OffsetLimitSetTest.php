<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set\Tests;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Kenny1911\Doctrine\Set\OffsetLimitSet;
use Kenny1911\Doctrine\Set\QuerySet;
use Kenny1911\Doctrine\Set\Tests\Entity\Foo;
use PHPUnit\Framework\TestCase;

final class OffsetLimitSetTest extends TestCase
{
    /** @var OffsetLimitSet */
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
        $query->setSQL('SELECT f.id FROM Foo f ORDER BY f.id');

        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(Foo::class, 'f.id');

        $query->setResultSetMapping($rsm);

        $this->set = new OffsetLimitSet(new QuerySet($query), 3, 3);
    }

    public function testGetSql(): void
    {
        $this->assertSame(
            'SELECT * FROM (SELECT f.id FROM Foo f ORDER BY f.id) AS records LIMIT 3 OFFSET 3',
            $this->set->getSql()
        );
    }

    public function testGetQuery(): void
    {
        /** @var array<Foo> $entities */
        $entities = $this->set->getQuery()->getResult();

        $this->assertCount(3, $entities);

        foreach ($entities as $entity) {
            $this->assertInstanceOf(Foo::class, $entity);
            $this->assertTrue(in_array($entity->getId(), [4,5,6], true));
        }
    }
}
