<?php

namespace WebDev\BlogBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use WebDev\BlogBundle\Service\BlogService;

class BlogExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('blog.route_prefix', $config['route_prefix']);
        $container->setParameter('blog.blogs_per_page', $config['blogs_per_page']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $definition = $container->findDefinition(BlogService::class);
        $definition->setArgument('$blogsPerPage', $config['blogs_per_page']);

        $bundlePath = \dirname(__DIR__);

        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'BlogBundle' => [
                        'type' => 'attribute',
                        'dir' => $bundlePath . '/Entity',
                        'prefix' => 'WebDev\\BlogBundle\\Entity',
                        'alias' => 'BlogBundle',
                    ],
                ],
            ],
        ]);
    }
}