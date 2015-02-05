<?php

namespace FOX\ReferenceDataLoaderBundle\Executor;

/**
 * Interface ConfigurationExecutorInterface
 */
interface ConfigurationExecutorInterface
{
    /**
     * beginTransaction
     */
    public function beginTransaction();

    /**
     * rollback
     */
    public function rollback();

    /**
     * commit
     */
    public function commit();

    /**
     * @param string $filePath
     *
     * @throws \Exception
     */
    public function execute($filePath);
}
