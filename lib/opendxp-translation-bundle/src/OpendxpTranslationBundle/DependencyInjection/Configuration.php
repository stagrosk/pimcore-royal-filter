<?php

declare(strict_types=1);

namespace OpendxpTranslationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('opendxp_translation');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC for symfony/config < 4.2
            $rootNode = $treeBuilder->root('opendxp_translation');
        }

        $rootNode
            ->children()
                ->scalarNode('api_key')
                    ->isRequired()
                ->end()
                ->scalarNode('source_lang')
                    ->isRequired()
                ->end()
                ->scalarNode('provider')
                    ->defaultValue('google_translate')
                ->end()
                ->scalarNode('formality')
                    ->defaultValue('default')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
