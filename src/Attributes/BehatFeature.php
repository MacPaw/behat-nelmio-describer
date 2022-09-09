<?php

namespace BehatNelmioDescriber\Attributes;

use Attribute;

/**
 * @Annotation
 */
#[\Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class BehatFeature
{
    public function __construct(
        public readonly string $status,
        public readonly string $file,
        public readonly array $anchors,
    ){
    }
}
