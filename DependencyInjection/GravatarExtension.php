<?php
namespace Bundle\GravatarBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GravatarExtension extends Extension
{
    public function configLoad($config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
        $loader->load('config.xml');

        $parameters = array();
        foreach (array('size', 'rating', 'default') as $key) {
            if (isset($config[$key])) {
                $parameters[$key] = $config[$key];
            }
        }

        $container->getDefinition('gravatar.api')->addArgument($parameters);
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return null;
    }

    public function getNamespace()
    {
        return 'http://www.symfony-project.org/schema/dic/symfony';
    }

    public function getAlias()
    {
        return 'gravatar';
    }
}
