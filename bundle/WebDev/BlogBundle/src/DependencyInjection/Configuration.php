<?php

namespace WebDev\BlogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('blog');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('route_prefix')
                    ->defaultValue('/blog')
                    ->cannotBeEmpty()
                    ->end()
                ->integerNode('blogs_per_page')
                    ->defaultValue(5)
                    ->min(1)
                    ->max(100)
                ->end()
            ->end();
        return $treeBuilder;
    }
}