<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('behat_api_doc_describer');
        $root = $treeBuilder->getRootNode()->children();

        $this->addBehatTestPathSection($root);

        return $treeBuilder;
    }

    private function addBehatTestPathSection(NodeBuilder $builder): void
    {
        $builder->scalarNode('behat_test_path')->cannotBeEmpty()->end();
    }
}
