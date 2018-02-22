<?php

namespace Ornicar\GravatarBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ornicar_gravatar', 'array');
        $rootNode
            ->children()
                ->scalarNode('size')->defaultValue('80')->end()
                ->scalarNode('rating')->defaultValue('g')->end()
                ->scalarNode('default')->defaultValue('mm')->end()
                ->booleanNode('secure')->defaultFalse()->end()
                ->scalarNode('cache_service')->end()
                ->scalarNode('cache_ttl')->defaultValue('300')->end()
            ->end();

        return $treeBuilder;
    }
}
