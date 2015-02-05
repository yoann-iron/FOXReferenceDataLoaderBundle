<?php

namespace FOX\ReferenceDataLoaderBundle\Configuration;

/**
 * Interface ConfigurationInterface
 */
interface ConfigurationInterface
{
    /**
     * Get file list to load data test
     *
     * @return array
     */
    public function getSqlFileList();

    /**
     * Get configuration name
     *
     * @return string
     */
    public function getName();

    /**
     * Get configuration description
     *
     * @return string
     */
    public function getDescription();
}
