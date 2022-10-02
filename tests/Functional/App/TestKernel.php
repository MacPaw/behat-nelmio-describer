<?php

namespace BehatNelmioDescriber\Tests\Functional\App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class TestKernel extends Kernel
{
    use MicroKernelTrait {
        registerContainerConfiguration as protected reg;
    }

    public function __construct()
    {
        parent::__construct('test', false);
    }

    public function registerBundles(): iterable
    {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new \BehatNelmioDescriber\BehatNelmioDescriberBundle(),
        ];

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/nelmio_api_doc.yaml');
        $loader->load(__DIR__ . '/config/sensio_framework_extra.yml');;
        $loader->load(__DIR__ . '/config/services.yaml');
        $loader->load(__DIR__ . '/config/behat_nelmio_describer.yaml');
        $loader->load(__DIR__ . '/config/framework.yaml');

        $this->reg($loader);
    }

    public function getCacheDir(): string
    {
        return __DIR__.'/var/cache';
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}