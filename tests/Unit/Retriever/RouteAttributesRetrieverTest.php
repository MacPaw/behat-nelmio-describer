<?php

declare(strict_types=1);

namespace BehatDoctrineFixtures\Tests\Unit\Database;

use BehatNelmioDescriber\Attributes\BehatFeature;
use BehatNelmioDescriber\Attributes\BehatFeaturesPath;
use BehatNelmioDescriber\Dto\RouteAttributesInfo;
use BehatNelmioDescriber\Enum\Status;
use BehatNelmioDescriber\Retriever\RouteAttributesRetriever;
use Nelmio\ApiDocBundle\Util\ControllerReflector;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteAttributesRetrieverTest extends TestCase
{
    /**
     * @dataProvider getAttributesProvider
     */
    public function testGetFileReferenceContent(
        string $controllerFeaturesPath,
        array $methodAttributes
    ): void
    {
        $methods = ['POST'];
        $expectedMethods = ['post'];
        $examplePath = 'example/path';
        $controllerString = 'some/controller';

        $route = self::createMock(Route::class);
        $route->expects(self::once())
            ->method('hasDefault')
            ->with('_controller')
            ->willReturn(true);
        $route->expects(self::once())
            ->method('getDefault')
            ->with('_controller')
            ->willReturn($controllerString);
        $route->expects(self::once())
            ->method('getPath')
            ->with()
            ->willReturn($examplePath);
        $route->expects(self::once())
            ->method('getMethods')
            ->with()
            ->willReturn($methods);

        $routeCollection = self::createMock(RouteCollection::class);
        $routeCollection->expects(self::once())
            ->method('all')
            ->with()
            ->willReturn([$route]);


        $controllerAttributeReflection = self::createMock(ReflectionAttribute::class);
        $controllerAttributeReflection->expects(self::once())
            ->method('getArguments')
            ->with()
            ->willReturn(['path' => $controllerFeaturesPath]);
        $controllerReflection = self::createMock(ReflectionClass::class);
        $controllerReflection->expects(self::once())
            ->method('getAttributes')
            ->with(BehatFeaturesPath::class)
            ->willReturn([$controllerAttributeReflection]);

        $methodReflection = self::createMock(ReflectionMethod::class);
        $methodReflection->expects(self::once())
            ->method('getAttributes')
            ->with(BehatFeature::class)
            ->willReturn($methodAttributes);
        $methodReflection->expects(self::once())
            ->method('getDeclaringClass')
            ->with()
            ->willReturn($controllerReflection);

        $controllerReflector = self::createMock(ControllerReflector::class);
        $controllerReflector->expects(self::once())
            ->method('getReflectionMethod')
            ->with($controllerString)
            ->willReturn($methodReflection);


        $routeAttributesRetriever = new RouteAttributesRetriever($routeCollection, $controllerReflector);

        $routesAttributes = $routeAttributesRetriever->getRouteAttributes();

        self::assertCount(1, $routesAttributes);

        $routeAttributesInfo = $routesAttributes[0];
        self::assertInstanceOf(RouteAttributesInfo::class, $routeAttributesInfo);
        self::assertEquals($controllerFeaturesPath, $routeAttributesInfo->getFeaturesPath());
        self::assertEquals($examplePath, $routeAttributesInfo->getRoutePath());
        self::assertEquals($expectedMethods, $routeAttributesInfo->getSupportedHttpMethods());
        self::assertEquals($methodAttributes, $routeAttributesInfo->getAttributes());
    }

    public function getAttributesProvider(): \Generator
    {
        yield [
            'sample/path',
            [
                new BehatFeature(Status::SUCCESS, 'test.feature', ['anchor'])
            ]
        ];
    }
}
