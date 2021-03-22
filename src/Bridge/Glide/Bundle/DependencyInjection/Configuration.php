<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $rootNode = ($treeBuilder = new TreeBuilder('glide'))->getRootNode();

        // @phpstan-ignore-next-line
        $rootNode
            ->children()
                ->scalarNode('base_url')
                    ->info('You can choose the path to use for the glide controller.')
                    ->defaultValue(null)
                ->end()
                ->scalarNode('sign_key')->defaultValue(null)->end()
                ->scalarNode('source')->isRequired()->end()
                ->scalarNode('cache')->info('Where the resized images files are cached.')->isRequired()->end()
                ->booleanNode('pre_generate')
                    ->info('Calling the Twig filters will pre-generate the images in cache before the request is served and return the public cache path directly. Use it to warmup the images cache when generating a static version of your site for instance.')
                    ->defaultValue(false)
                ->end()
                ->scalarNode('public_dir')
                    ->info('The path to the web public dir')
                    ->defaultValue('%kernel.project_dir%/public')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('presets')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            // The available Glide options:
                            ->scalarNode('or')->defaultNull()->end()
                            ->scalarNode('crop')->defaultNull()->end()
                            ->integerNode('w')->info('width')->defaultNull()->end()
                            ->integerNode('h')->info('height')->defaultNull()->end()
                            ->scalarNode('fit')->defaultNull()->end()
                            ->integerNode('dpr')->defaultNull()->end()
                            ->integerNode('bri')->defaultNull()->end()
                            ->integerNode('con')->defaultNull()->end()
                            ->floatNode('gam')->defaultNull()->end()
                            ->integerNode('sharp')->defaultNull()->end()
                            ->integerNode('blur')->defaultNull()->end()
                            ->integerNode('pixel')->defaultNull()->end()
                            ->scalarNode('filt')->defaultNull()->end()
                            ->scalarNode('mark')->defaultNull()->end()
                            ->scalarNode('markw')->defaultNull()->end()
                            ->scalarNode('markh')->defaultNull()->end()
                            ->scalarNode('markx')->defaultNull()->end()
                            ->scalarNode('marky')->defaultNull()->end()
                            ->scalarNode('markpad')->defaultNull()->end()
                            ->scalarNode('markpos')->defaultNull()->end()
                            ->scalarNode('markalpha')->defaultNull()->end()
                            ->scalarNode('bg')->defaultNull()->end()
                            ->scalarNode('border')->defaultNull()->end()
                            ->integerNode('q')->defaultNull()->end()
                            ->scalarNode('fm')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
