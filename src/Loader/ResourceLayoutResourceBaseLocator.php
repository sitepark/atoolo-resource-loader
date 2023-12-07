<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\ResourceBaseLocator;

/**
 * @backupStaticAttributes enabled
 */
class ResourceLayoutResourceBaseLocator implements ResourceBaseLocator
{
    public function locate(): string
    {
        if (empty($_SERVER['RESOURCE_ROOT'])) {
            throw new \RuntimeException(
                'missing server variable RESOURCE_ROOT'
            );
        }
        $resourceRoot = $_SERVER['RESOURCE_ROOT'];
        return $resourceRoot . '/objects';
    }
}
