<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\ResourceBaseLocator;

class StaticResourceBaseLocator implements ResourceBaseLocator
{
    public function __construct(private readonly string $resourceBase)
    {
    }

    public function locate(): string
    {
        return $this->resourceBase;
    }
}
