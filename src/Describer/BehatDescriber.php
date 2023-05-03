<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Describer;

use BehatNelmioDescriber\Retriever\RouteAttributesRetriever;
use BehatNelmioDescriber\Retriever\FileContentRetriever;
use Nelmio\ApiDocBundle\Describer\DescriberInterface;
use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use OpenApi\Annotations as OA;

class BehatDescriber implements DescriberInterface
{
    private const INVALID_REFERENCE_MESSAGE = '[Invalid reference]';

    public function __construct(
        private readonly RouteAttributesRetriever $routeAttributesRetriever,
        private readonly FileContentRetriever $fileContentRetriever,
    ) {
    }

    public function describe(OA\OpenApi $api): void
    {
        foreach ($this->routeAttributesRetriever->getRouteAttributes() as $attributeInfo) {
            $attributes = $attributeInfo->getAttributes();

            if (count($attributes) == 0) {
                continue;
            }

            foreach ($attributeInfo->getSupportedHttpMethods() as $httpMethod) {
                $path = Util::getPath($api, $attributeInfo->getRoutePath());
                $operation = Util::getOperation($path, $httpMethod);

                if (!$operation instanceof OA\Operation) {
                    continue;
                }

                $examples = [];
                foreach ($attributes as $attribute) {
                    $arguments = $attribute->getArguments();
                    $blockName = $arguments['status'];
                    $fileReference = $attributeInfo->getFeaturesPath() . $arguments['file'];
                    $anchorList = array_unique($arguments['anchors']);

                    foreach ($anchorList as $anchor) {
                        $content = $this->fileContentRetriever->getFileReferenceContent($fileReference, $anchor);

                        if ($content === null) {
                            $content = self::INVALID_REFERENCE_MESSAGE;
                        }

                        if (($json = json_decode($content)) !== null) {
                            $content = json_encode($json, JSON_PRETTY_PRINT);
                        }

                        $examples[$blockName][] = $content;
                    }
                }

                $documentations = '';
                foreach ($examples as $blockName => $contents) {
                    /** @var array $contents */
                    $contents = array_unique($contents, SORT_REGULAR);

                    $documentations .= "<details><summary>**$blockName**</summary>\n";
                    foreach ($contents as $content) {
                        $documentations .= sprintf("```\n%s\n```\n", rtrim($content));
                    }
                    $documentations .= "</details>\n";
                }

                $operation->description = $documentations;
            }
        }
    }
}
