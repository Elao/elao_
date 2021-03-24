<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle\DependencyInjection;

use App\Bridge\Glide\Bundle\Controller\ResizeImageController;
use App\Bridge\Glide\Bundle\GlideUrlBuilder;
use App\Bridge\Glide\Bundle\Twig\GlideExtension as TwigGlideExtension;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\Server;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Filesystem\Filesystem as Fs;

class GlideExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $locator = new FileLocator(__DIR__ . '/../Resources/config/');
        $resolver = new LoaderResolver([
            new YamlFileLoader($container, $locator),
            new DirectoryLoader($container, $locator),
        ]);
        $loader = new DelegatingLoader($resolver);
        $loader->load('services/');

        $signKey = $config['sign_key'];
        $baseUrl = $config['base_url'];
        $container->setParameter('glide_base_url', $config['base_url']);
        $container->getDefinition(GlideUrlBuilder::class)
            ->replaceArgument(1, $baseUrl)
            ->replaceArgument(2, $signKey)
        ;
        $container->getDefinition(ResizeImageController::class)->replaceArgument(1, $signKey);
        $container->getDefinition(TwigGlideExtension::class)
            ->replaceArgument(2, array_keys($config['presets']))
            ->replaceArgument(3, $config['pre_generate'])
        ;

        $this->configureServer(
            $config['source'],
            $config['cache'],
            $container,
            $config['presets'],
        );

        $publicCacheDir = (new Fs())->makePathRelative(
            $container->getParameterBag()->resolveValue($config['cache']),
            $container->getParameterBag()->resolveValue($config['public_dir']),
        );
        $container->setParameter('glide_public_cache_path', trim($publicCacheDir, '/'));
    }

    public function configureServer(string $source, string $cache, ContainerBuilder $container, array $presets = []): void
    {
        $container->register('glide_source', Local::class)->setArguments([$source]);
        $container->register('glide_cache', Local::class)->setArguments([$cache]);

        $container->register('glide_source_fs', Filesystem::class)->setArgument(0, new Reference('glide_source'));
        $container->register('glide_cache_fs', Filesystem::class)->setArgument(0, new Reference('glide_cache'));

        $container->getDefinition(Server::class)->replaceArgument(0, [
            'source' => new Reference('glide_source_fs'),
            'cache' => new Reference('glide_cache_fs'),
            'response' => new Reference(SymfonyResponseFactory::class),
            'defaults' => [],
            'presets' => $presets,
        ])
        ->setPublic(true);
    }
}
