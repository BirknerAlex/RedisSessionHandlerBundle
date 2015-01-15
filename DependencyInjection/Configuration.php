<?php

namespace BirknerAlex\RedisSessionHandlerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('redis_session_handler');
        $rootNode
            ->children()
                ->scalarNode('host')->end()
                ->scalarNode('password')->end()
                ->scalarNode('port')->end()
                ->scalarNode('database')->end()
                ->arrayNode('db_options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('prefix')->end()
                        ->scalarNode('expiretime')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
