<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Attributes;

use Attribute;

/**
 * @Annotation
 */
#[\Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class BehatFeature
{
    public function __construct(
        public string $status,
        public string $file,
        public array $anchors,
    ) {
    }
}
