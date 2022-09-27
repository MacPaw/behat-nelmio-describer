<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Retriever;

use BehatNelmioDescriber\Attributes\BehatFeature;
use BehatNelmioDescriber\Attributes\BehatFeaturesPath;
use BehatNelmioDescriber\Dto\RouteAttributesInfo;
use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use Nelmio\ApiDocBundle\Util\ControllerReflector;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteAttributesRetriever
{
    public function __construct(
        private RouteCollection $routeCollection,
        private ControllerReflector $controllerReflector
    ) {
    }

    public function getRouteAttributes(): array
    {
        $result = [];

        foreach ($this->routeCollection->all() as $route) {
            if (!$route->hasDefault('_controller')) {
                continue;
            }

            $controller = $route->getDefault('_controller');
            $reflectionMethod = $this->controllerReflector->getReflectionMethod($controller);

            $featuresPathAttributes = $reflectionMethod->getDeclaringClass()->getAttributes(BehatFeaturesPath::class);

            $featuresPath = '';
            if(count($featuresPathAttributes) > 0) {
                $featuresPath = $featuresPathAttributes[0]->getArguments()['path'];
            }

            $result[] = new RouteAttributesInfo(
                $this->normalizePath($route->getPath()),
                $featuresPath,
                $this->getSupportedHttpMethods($route),
                $reflectionMethod->getAttributes(BehatFeature::class)
            );
        }

        return $result;
    }

    private function getSupportedHttpMethods(Route $route): array
    {
        $allMethods = Util::OPERATIONS;
        $methods = array_map('strtolower', $route->getMethods());

        return array_intersect($methods ?: $allMethods, $allMethods);
    }

    private function normalizePath(string $path): string
    {
        if ('.{_format}' === substr($path, -10)) {
            $path = substr($path, 0, -10);
        }

        return $path;
    }
}
