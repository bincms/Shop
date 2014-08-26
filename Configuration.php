<?php

namespace Extension\Shop;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('Shop');

        $rootNode->children()->integerNode('reserveDays')->defaultValue(1)->end();

        $this->addImportSection($rootNode);

        return $treeBuilder;
    }

    private function addImportSection($rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('import')
                    ->children()
                        ->scalarNode('login')->end()
                        ->scalarNode('password')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}