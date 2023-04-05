<?php

declare(strict_types=1);

namespace Kenny1911\Doctrine\Set\Tests;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Kenny1911\Doctrine\Set\QuerySet;
use Kenny1911\Doctrine\Set\Tests\Entity\Foo;
use PHPUnit\Framework\TestCase;

final class QuerySetTest extends TestCase
{
    /** @var QuerySet */
    private $set;

    /**
     * @throws MissingMappingDriverImplementation
     * @throws Exception
     */
    protected function setUp(): void
    {
        // Init database
        $em = DB::init();
        DB::fill($em);

        // Init Query
        $query = new NativeQuery($em);
        $query->setSQL('SELECT f.id FROM Foo f WHERE f.id >= :minId AND f.id <= :maxId');
        $query->setParameter('minId', 4);
        $query->setParameter('maxId', 6);

        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(Foo::class, 'f.id');

        $query->setResultSetMapping($rsm);

        // Init set
        $this->set = new QuerySet($query);
    }

    public function testGetSql(): void
    {
        $this->assertSame('SELECT f.id FROM Foo f WHERE f.id >= :minId AND f.id <= :maxId', $this->set->getSql());
    }

    public function testGetParameters(): void
    {
        $parameters = [];

        /** @var Parameter $parameter */
        foreach ($this->set->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = $parameter->getValue();
        }

        $this->assertSame(['minId' => 4, 'maxId' => 6], $parameters);
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
