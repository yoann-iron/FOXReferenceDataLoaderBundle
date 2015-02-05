<?php

namespace GlobalPlatform\Bundle\DomainBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GlobalPlatformDomainExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('bh_domain.email.attachment_directory', $container->getParameter('kernel.root_dir') . $config['email']['attachment_directory']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('behavior.xml');
        $loader->load('builder.xml');
        $loader->load('configuration.xml');
        $loader->load('executor.xml');
        $loader->load('factory.xml');
        $loader->load('generator.xml');
        $loader->load('managers.xml');
        $loader->load('provider.xml');
        $loader->load('repositories.xml');
        $loader->load('services.xml');
        $loader->load('subscriber.xml');
        $loader->load('validator.xml');
    }
}
