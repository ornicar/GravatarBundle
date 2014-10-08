<?php
namespace Ornicar\GravatarBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

class OrnicarGravatarExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(array(__DIR__.'/../Resources/config')));
        $loader->load('config.xml');

        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->process($configuration->getConfigTree(), $configs);

        $container->getDefinition('gravatar.api')->addArgument($config);

        if (isset($config['cache']['adapter'])) {
            $cache = $config['cache']['adapter'];
            $container->getDefinition('gravatar.api')->addMethodCall(
                'setCache',
                array(
                    new Reference(
                        $cache,
                        ContainerInterface::IGNORE_ON_INVALID_REFERENCE
                    )
                )
            );
        }

        if (isset($config['cache']['lifetime'])) {
            $lifetime = $config['cache']['lifetime'];
            $container->getDefinition('gravatar.api')->addMethodCall(
                'setLifetime',
                array($lifetime)
            );
        }
    }
}
