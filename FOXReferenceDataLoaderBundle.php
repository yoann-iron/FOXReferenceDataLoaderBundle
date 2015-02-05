<?php

namespace GlobalPlatform\Bundle\DomainBundle;

use GlobalPlatform\Bundle\DomainBundle\DependencyInjection\Compiler\ConfigurationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class GlobalPlatformDomainBundle
 */
class GlobalPlatformDomainBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationCompilerPass());
    }
}
