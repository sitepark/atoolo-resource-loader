<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

/**
 * In some cases, for example with command line calls, the resource loader
 * cannot be generated directly via dependency-injecten because the $basePath
 * is not yet known. In this case, the SiteKitLoaderFactory can be used.
 */
class SiteKitLoaderFactory
{
    public function create(string $basePath): SiteKitLoader
    {
        return new SiteKitLoader($basePath);
    }
}
