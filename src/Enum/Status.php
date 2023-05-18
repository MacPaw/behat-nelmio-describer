<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Enum;

enum Status: string
{
    case SUCCESS = 'success';
    case FAILURE = 'failure';
}
