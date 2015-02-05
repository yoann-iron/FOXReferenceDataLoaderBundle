<?php

namespace FOX\ReferenceDataLoaderBundle\Configuration;

use FOX\ReferenceDataLoaderBundle\Exception\ConfigurationNotFoundException;
use FOX\ReferenceDataLoaderBundle\Configuration\ConfigurationInterface;

/**
 * Interface ConfigurationChainInterface
 */
interface ConfigurationChainInterface
{
    /**
     * @param ConfigurationInterface $configuration
     */
    public function addConfiguration(ConfigurationInterface $configuration);

    /**
     * @param string $identifier
     *
     * @return ConfigurationInterface|ConfigurationNotFoundException
     */
    public function getConfiguration($identifier);
}
