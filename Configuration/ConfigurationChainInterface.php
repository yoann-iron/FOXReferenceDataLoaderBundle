<?php

namespace GlobalPlatform\Bundle\DomainBundle\Configuration;

use GlobalPlatform\Bundle\DomainBundle\Exception\ConfigurationNotFoundException;
use GlobalPlatform\Bundle\DomainBundle\Configuration\ConfigurationInterface;

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
