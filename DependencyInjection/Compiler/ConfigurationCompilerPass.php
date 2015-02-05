<?php

namespace GlobalPlatform\Bundle\DomainBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ConfigurationCompilerPass
 */
class ConfigurationCompilerPass implements CompilerPassInterface
{
    const METHOD_CALLED       = 'addConfiguration';
    const CONFIGURATION_CHAIN = 'gp_domain.configuration.chain';
    const CONFIGURATION_TAG   = 'gp_domain.configuration';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::CONFIGURATION_CHAIN)) {
            return;
        }

        $definition = $container->getDefinition(self::CONFIGURATION_CHAIN);

        foreach ($container->findTaggedServiceIds(self::CONFIGURATION_TAG) as $id => $attributes) {
            $definition->addMethodCall(
                self::METHOD_CALLED,
                array(new Reference($id))
            );
        }
    }
}
