<?php

namespace FOX\ReferenceDataLoaderBundle\Configuration;

use FOX\ReferenceDataLoaderBundle\Exception\ConfigurationNotFoundException;

/**
 * Class ConfigurationChain
 */
class ConfigurationChain implements ConfigurationChainInterface
{
    /**
     * @var ConfigurationInterface[]
     */
    protected $configurationList;

    /**
     * @return ConfigurationInterface[]
     */
    public function getConfigurationList()
    {
        return $this->configurationList;
    }

    /**
     * @param ConfigurationInterface[] $configurationList
     */
    public function setConfigurationList($configurationList)
    {
        foreach ($configurationList as $configuration) {
            $this->addConfiguration($configuration);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(ConfigurationInterface $configuration)
    {
        $this->configurationList[$configuration->getName()] = $configuration;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration($identifier)
    {
        if (isset($this->configurationList[$identifier])) {
            return $this->configurationList[$identifier];
        }

        throw new ConfigurationNotFoundException(sprintf('The configuration #%s doesn\'t exist', $identifier));
    }
}
