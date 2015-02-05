<?php

namespace GlobalPlatform\Bundle\DomainBundle\Configuration;

/**
 * Class BaseConfiguration
 */
abstract class BaseConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return static::CONFIGURATION_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return static::CONFIGURATION_DESCRIPTION;
    }
} 
