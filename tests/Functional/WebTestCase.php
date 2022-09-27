<?php

namespace BehatNelmioDescriber\Tests\Functional;

use BehatNelmioDescriber\Tests\Functional\App\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class WebTestCase extends BaseWebTestCase
{
    protected static function createKernel(array $options = []): KernelInterface
    {
        return new TestKernel();
    }

    protected static function getContainer(): ContainerInterface
    {
        $kernel = new TestKernel();
        $kernel->boot();

        return $kernel->getContainer();
    }
}