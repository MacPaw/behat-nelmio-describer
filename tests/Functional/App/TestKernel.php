<?php

namespace BehatNelmioDescriber\Tests\Functional\App;

use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

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

    protected function configureRoutes($routes)
    {
        dd(1);
//        $this->import($routes, __DIR__.'/Resources/routes.yaml', '/', 'yaml');
//
//        if (class_exists(SerializedName::class)) {
//            $this->import($routes, __DIR__.'/Controller/SerializedNameController.php', '/', 'annotation');
//        }
//
//        if ($this->flags & self::USE_JMS) {
//            $this->import($routes, __DIR__.'/Controller/JMSController.php', '/', 'annotation');
//        }
//
//        if ($this->flags & self::USE_BAZINGA) {
//            $this->import($routes, __DIR__.'/Controller/BazingaController.php', '/', 'annotation');
//
//            try {
//                new \ReflectionMethod(Embedded::class, 'getType');
//                $this->import($routes, __DIR__.'/Controller/BazingaTypedController.php', '/', 'annotation');
//            } catch (\ReflectionException $e) {
//            }
//        }
//
//        if ($this->flags & self::ERROR_ARRAY_ITEMS) {
//            $this->import($routes, __DIR__.'/Controller/ArrayItemsErrorController.php', '/', 'annotation');
//        }
    }
//
//    /**
//     * BC for sf < 5.1.
//     */
//    private function import($routes, $resource, $prefix, $type)
//    {
//        if ($routes instanceof RoutingConfigurator) {
//            $routes->withPath($prefix)->import($resource, $type);
//        } else {
//            $routes->import($resource, $prefix, $type);
//        }
//    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/nelmio_api_doc.yaml');
        $loader->load(__DIR__ . '/config/sensio_framework_extra.yml');;
        $loader->load(__DIR__ . '/config/services.yaml');
        $loader->load(__DIR__ . '/config/behat_nelmio_describer.yaml');
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