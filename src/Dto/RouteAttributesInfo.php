<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Dto;

class RouteAttributesInfo
{
    public function __construct(
        private string $routePath,
        private string $featuresPath,
        private array $supportedHttpMethods,
        private array $attributes
    ) {
    }

    public function getRoutePath(): string
    {
        return $this->routePath;
    }

    public function getFeaturesPath(): string
    {
        return $this->featuresPath;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getSupportedHttpMethods(): array
    {
        return $this->supportedHttpMethods;
    }
}
