<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Executor;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Connection;
use GlobalPlatform\Bundle\DomainBundle\Executor\ConfigurationDoctrineExecutor;
use Phake;

    /**
 * Class ConfigurationDoctrineExecutorTest
 */
class ConfigurationDoctrineExecutorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection $connection
     */
    protected $connectionMock;

    /**
     * @var ConfigurationDoctrineExecutor
     */
    protected $configurationDoctrineExecutor;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->connectionMock = Phake::mock(Connection::class);

        $entityManager = Phake::mock(EntityManager::class);
        Phake::when($entityManager)->getConnection()->thenReturn($this->connectionMock);

        $this->configurationDoctrineExecutor = new ConfigurationDoctrineExecutor($entityManager);
    }

    public function testBeginTransaction()
    {
        $this->configurationDoctrineExecutor->beginTransaction();

        Phake::verify($this->connectionMock, Phake::times(1))->beginTransaction();
    }

    public function testRollback()
    {
        $this->configurationDoctrineExecutor->rollBack();

        Phake::verify($this->connectionMock, Phake::times(1))->rollBack();
    }

    public function testCommit()
    {
        $this->configurationDoctrineExecutor->commit();

        Phake::verify($this->connectionMock, Phake::times(1))->commit();
    }
}
