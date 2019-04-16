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
        if (\method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder('ornicar_gravatar');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('ornicar_gravatar', 'array');
        }
        $rootNode
            ->children()
                ->scalarNode('size')->defaultValue('80')->end()
                ->scalarNode('rating')->defaultValue('g')->end()
                ->scalarNode('default')->defaultValue('mm')->end()
                ->booleanNode('secure')->defaultFalse()->end()
            ->end();

        return $treeBuilder;
    }
}
