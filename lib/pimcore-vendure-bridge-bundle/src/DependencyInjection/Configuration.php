<?php

namespace PimcoreVendureBridgeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pimcore_vendure_bridge');

        $root = $treeBuilder->getRootNode();
        $root
            ->children()
                ->variableNode('allowed_object_types')
                ->defaultValue(['Product', 'Customer'])
            ->end();

        return $treeBuilder;
    }
}
