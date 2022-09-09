<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\DependencyInjection;

use BehatNelmioDescriber\Describer\BehatDescriber;
use BehatNelmioDescriber\Retriever\FileContentRetriever;
use BehatNelmioDescriber\Retriever\RouteAttributesRetriever;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class BehatNelmioDescriberExtension extends Extension
{
    /**
     * @param array<array> $configs
     *
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $this->loadApiDocDescriber($config, $loader, $container);
    }

    private function loadApiDocDescriber(array $config, XmlFileLoader $loader, ContainerBuilder $container): void {
        $loader->load('file_content_retriever.xml');

        $fileContentRetrieverDefinition = $container->getDefinition(FileContentRetriever::class);
        $fileContentRetrieverDefinition->setArgument('$behatTestPath', $config['behat_test_path']);

        foreach ($container->getParameter('nelmio_api_doc.areas') as $area) {
            $container->register(
                sprintf('behat_api_doc_describer.route_attributes_retriever.%s', $area),
                RouteAttributesRetriever::class
            )
                ->setPublic(false)
                ->setArguments([
                    new Reference(sprintf('nelmio_api_doc.routes.%s', $area)),
                    new Reference('nelmio_api_doc.controller_reflector')
                ]);

            $container->register(
                sprintf('behat_api_doc_describer.behat_describer.%s', $area),
                BehatDescriber::class
            )
                ->setPublic(false)
                ->setArguments([
                    new Reference(sprintf('behat_api_doc_describer.route_attributes_retriever.%s', $area)),
                    new Reference(FileContentRetriever::class)
                ])
                ->addTag(sprintf('nelmio_api_doc.describer.%s', $area), ['priority' => -1000]);
        }
    }
}
