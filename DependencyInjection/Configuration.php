<?php

namespace Speicher210\CloudinaryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('speicher210_cloudinary');

        $rootNode
            ->children()
                ->scalarNode('url')
                    ->info('Any parameter value parsed from this string will override explicitly set parameters.')
                    ->validate()
                        ->ifTrue(
                            function ($url) {
                                $url = parse_url($url);
                                return $url === false || !array_key_exists('scheme', $url);
                            }
                        )
                        ->thenInvalid('Cloudinary URL must be in the form: cloudinary://api_key:api_secret@cloud_name')
                    ->end()
                ->end()
                ->scalarNode('cloud_name')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('access_identifier')
                    ->children()
                        ->scalarNode('api_key')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('api_secret')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
