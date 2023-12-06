<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\ResourceBaseLocator;

class ResourceLayoutResourceBaseLocator implements ResourceBaseLocator
{
    public function locate(): string
    {
        $resourceRoot = $_SERVER['RESOURCE_ROOT'];
        return $resourceRoot . '/objects';
    }
}
