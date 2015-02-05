<?php

namespace GlobalPlatform\Bundle\DomainBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bh_domain');

        $this->addEmailConfiguration($rootNode);

        return $treeBuilder;
    }

    /**
     * Add email definition for AttachedEmail entity
     *
     * @param ArrayNodeDefinition $rootNode
     */
    public function addEmailConfiguration(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('email')
                ->addDefaultsIfNotSet()
                ->info('Define the directory for email attachments')
                ->children()
                    ->scalarNode('attachment_directory')
                    ->defaultValue('/../email_received/attachment/')
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ->end();
    }
}
