<?php

namespace BehatNelmioDescriber\Attributes;

use Attribute;

/**
 * @Annotation
 */
#[\Attribute(Attribute::TARGET_CLASS)]
class BehatFeaturesPath
{
    public function __construct(public readonly string $path){
    }
}
