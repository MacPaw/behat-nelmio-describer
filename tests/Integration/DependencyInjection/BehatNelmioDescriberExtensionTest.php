<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Tests\Integration\DependencyInjection;

use BehatNelmioDescriber\DependencyInjection\BehatNelmioDescriberExtension;
use BehatNelmioDescriber\Describer\BehatDescriber;
use BehatNelmioDescriber\Retriever\FileContentRetriever;
use BehatNelmioDescriber\Retriever\RouteAttributesRetriever;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class BehatNelmioDescriberExtensionTest extends TestCase
{
    public function testWithFilledConfig(): void
    {
        $container = $this->createContainerFromFixture('bundle_config');

        $fileContentRetrieverDefinition = $container->getDefinition(FileContentRetriever::class);
        self::assertSame(FileContentRetriever::class, $fileContentRetrieverDefinition->getClass());
        self::assertSame('/tests/Behat/Features', $fileContentRetrieverDefinition->getArgument('$behatTestPath'));

        $fileContentRetrieverDefinition = $container->getDefinition(
            'behat_api_doc_describer.route_attributes_retriever.default'
        );
        self::assertSame(RouteAttributesRetriever::class, $fileContentRetrieverDefinition->getClass());
        self::assertFalse($fileContentRetrieverDefinition->isPublic());

        self::assertSame(
            'nelmio_api_doc.routes.default',
            (string) $fileContentRetrieverDefinition->getArgument(0)
        );
        self::assertSame(
            'nelmio_api_doc.controller_reflector',
            (string) $fileContentRetrieverDefinition->getArgument(1)
        );

        $behatDescriberDefinition = $container->getDefinition('behat_api_doc_describer.behat_describer.default');
        self::assertSame(BehatDescriber::class, $behatDescriberDefinition->getClass());
        self::assertFalse($behatDescriberDefinition->isPublic());

        self::assertSame(
            'behat_api_doc_describer.route_attributes_retriever.default',
            (string) $behatDescriberDefinition->getArgument(0)
        );
        self::assertSame(
            FileContentRetriever::class,
            (string) $behatDescriberDefinition->getArgument(1)
        );

        self::assertCount(1, $behatDescriberDefinition->getTags());
        self::assertEquals(
            [['priority' => -1000]],
            $behatDescriberDefinition->getTag('nelmio_api_doc.describer.default')
        );
    }

    private function createContainerFromFixture(string $fixtureFile): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $container->registerExtension(new BehatNelmioDescriberExtension());
        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);

        $container->setParameter('nelmio_api_doc.areas', ['default']);
        $this->loadFixture($container, $fixtureFile);
        $container->compile();

        return $container;
    }

    protected function loadFixture(ContainerBuilder $container, string $fixtureFile): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../Fixtures/Configuration'));
        $loader->load($fixtureFile . '.yaml');
    }
}
