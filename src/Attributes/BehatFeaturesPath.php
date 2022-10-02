<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Attributes;

use Attribute;

/**
 * @Annotation
 */
#[\Attribute(Attribute::TARGET_CLASS)]
class BehatFeaturesPath
{
    public function __construct(public string $path)
    {
    }
}
