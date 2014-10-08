<?php

namespace Ornicar\GravatarBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\Config\Definition\NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gravatar', 'array');
        $rootNode
                ->children()
                ->scalarNode('size')->defaultValue('80')->end()
                ->scalarNode('rating')->defaultValue('g')->end()
                ->scalarNode('default')->defaultValue('mm')->end()
                ->booleanNode('secure')->defaultFalse()->end()
                ->arrayNode('cache')
                    ->children()
                        ->scalarNode('adapter')->defaultNull()->end()
                        ->integerNode('lifetime')->min(0)->cannotBeEmpty()->defaultValue(3600)->end()
                    ->end()
            ->end();

        return $treeBuilder->buildTree();
    }
}