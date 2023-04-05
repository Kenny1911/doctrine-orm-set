<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set\Tests;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Kenny1911\Doctrine\Set\ExceptSet;
use Kenny1911\Doctrine\Set\QuerySet;
use Kenny1911\Doctrine\Set\Tests\Entity\Foo;
use PHPUnit\Framework\TestCase;

final class ExceptSetTest extends TestCase
{
    /** @var ExceptSet */
    private $set;
    /**
     * @throws MissingMappingDriverImplementation
     * @throws Exception
     */
    protected function setUp(): void
    {
        $em = DB::init();
        DB::fill($em);

        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(Foo::class, 'f.id');

        $query1 = new NativeQuery($em);
        $query1->setSQL('SELECT f.id FROM Foo f WHERE f.id IN (:query1Ids)');
        $query1->setParameter('query1Ids', [1,2,3,4,5]);
        $query1->setResultSetMapping($rsm);

        $query2 = new NativeQuery($em);
        $query2->setSQL('SELECT f.id FROM Foo f WHERE f.id IN (:query2Ids)');
        $query2->setParameter('query2Ids', [3,4,5]);
        $query2->setResultSetMapping($rsm);

        $this->set = new ExceptSet([
            new QuerySet($query1),
            new QuerySet($query2)
        ]);
    }

    public function testGetSql(): void
    {
        $this->assertSame(
            'SELECT f.id FROM Foo f WHERE f.id IN (:query1Ids) EXCEPT SELECT f.id FROM Foo f WHERE f.id IN (:query2Ids)',
            $this->set->getSql()
        );
    }

    public function testGetParameters(): void
    {
        $parameters = [];

        /** @var Parameter $parameter */
        foreach ($this->set->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = $parameter->getValue();
        }

        $this->assertSame(
            ['query1Ids' => [1,2,3,4,5], 'query2Ids' => [3,4,5]],
            $parameters
        );
    }

    public function testGetQuery(): void
    {
        /** @var array<Foo> $entities */
        $entities = $this->set->getQuery()->getResult();

        $this->assertCount(2, $entities);

        foreach ($entities as $entity) {
            $this->assertInstanceOf(Foo::class, $entity);
            $this->assertTrue(in_array($entity->getId(), [1,2], true));
        }
    }
}
