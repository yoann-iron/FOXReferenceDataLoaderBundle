<?php

namespace GlobalPlatform\Bundle\DomainBundle\Executor;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Connection;

/**
 * Class ConfigurationDoctrineExecutor
 *
 */
class ConfigurationDoctrineExecutor implements ConfigurationExecutorInterface
{
    /**
     * @var Connection $connection
     */
    protected $connection;

    /**
     * Construct
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->connection = $entityManager->getConnection();
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * {@inheritDoc}
     */
    public function rollBack()
    {
        $this->connection->rollBack();
    }

    /**
     * {@inheritDoc}
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * {@inheritDoc}
     */
    public function execute($filePath)
    {
        $stmt = $this->connection->prepare($this->getSql($filePath));
        $stmt->execute();
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getSql($filePath)
    {
        return file_get_contents($filePath);
    }
}
