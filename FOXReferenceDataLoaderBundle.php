<?php

namespace FOX\ReferenceDataLoaderBundle;

use FOX\ReferenceDataLoaderBundle\DependencyInjection\Compiler\ConfigurationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class FOXReferenceDataLoaderBundle
 */
class FOXReferenceDataLoaderBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationCompilerPass());
    }
}
