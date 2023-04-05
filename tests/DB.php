<?php
declare(strict_types=1);

namespace Kenny1911\Doctrine\Set\Tests;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Kenny1911\Doctrine\Set\Tests\Entity\Foo;

final class DB
{
    /**
     * @throws MissingMappingDriverImplementation
     * @throws Exception
     */
    public static function init(): EntityManagerInterface
    {
        $config = ORMSetup::createConfiguration(true);
        $config->setMetadataDriverImpl(new SimplifiedXmlDriver([__DIR__.'/Entity/mapping' => 'Kenny1911\Doctrine\Set\Tests\Entity']));

        $connection = DriverManager::getConnection(
            [
                'driver' => 'pdo_sqlite',
                'memory' => true,
            ],
            $config
        );

        $em = new EntityManager($connection, $config);

        $schemaTool = new SchemaTool($em);
        $schemaTool->updateSchema($em->getMetadataFactory()->getAllMetadata());

        return $em;
    }

    public static function fill(EntityManagerInterface $em) {
        for ($i = 0; $i < 10; ++$i) {
            $em->persist(new Foo('foo_'.$i));
        }

        $em->flush();
    }
}
