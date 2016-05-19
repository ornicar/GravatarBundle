<?php

namespace Ornicar\GravatarBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class OrnicarGravatarExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('config.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->getDefinition('gravatar.client.real')->replaceArgument(0, array(
            'size'    => $config['size'],
            'rating'  => $config['rating'],
            'default' => $config['default'],
            'secure'  => $config['secure'],
        ));

        if ($config['cache']) {
            $container->getDefinition('gravatar.client.cached')->setAbstract(false);
            $container->getDefinition('gravatar.client.cached')->replaceArgument(1, new Reference($config['cache']));
            $container->setAlias('gravatar.client', 'gravatar.client.cached');
        } else {
            $container->setAlias('gravatar.client', 'gravatar.client.real');
        }
    }
}
