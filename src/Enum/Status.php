<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Enum;

use MyCLabs\Enum\Enum;

class Status extends Enum
{
    public const SUCCESS = 'success';
    public const FAILURE = 'failure';
}
