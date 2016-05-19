<?php

namespace Ornicar\GravatarBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ornicar_gravatar', 'array');
        $rootNode
            ->children()
                ->scalarNode('size')
                    ->defaultValue('80')
                    ->info('Default size of avatars')
                ->end()

                ->scalarNode('rating')->defaultValue('g')->end()
                ->scalarNode('default')->defaultValue('mm')->end()

                ->booleanNode('secure')
                    ->defaultFalse()
                    ->info('Use secure connection?')
                ->end()

                ->scalarNode('cache')
                    ->defaultValue('gravatar.cache.filesystem')
                    ->info('The service ID of cache layer')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
