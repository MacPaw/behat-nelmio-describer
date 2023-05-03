<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Attributes;

use Attribute;
use BehatNelmioDescriber\Enum\Status;

/**
 * @Annotation
 */
#[\Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class BehatFeature
{
    public string $status;

    public function __construct(
        Status $status,
        public string $file,
        public array $anchors,
    ) {
        $this->status = $status->value;
    }
}
