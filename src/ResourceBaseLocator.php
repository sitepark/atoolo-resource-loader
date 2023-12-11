<?php

declare(strict_types=1);

namespace Atoolo\Resource;

/**
 * The basic list of resources depends on the publication channel
 * and must be able to be determined dynamically. A separate
 * ResouceBaseLocator can be implemented for each publication layout.
 */
interface ResourceBaseLocator
{
    public function locate(): string;
}
