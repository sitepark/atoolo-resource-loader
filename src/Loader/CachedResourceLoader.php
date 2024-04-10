<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLoader;

/**
 * The CachedResourceLoader class is used to load resources
 * from a given location and cache them for future use.
 * The cache is stored in memory and is not persistent.
 */
class CachedResourceLoader implements ResourceLoader
{
    /**
     * @var array<string, Resource>
     */
    private array $cache = [];
    public function __construct(
        private readonly ResourceLoader $resourceLoader,
    ) {
    }

    /**
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function load(string $location, string $lang = ''): Resource
    {
        $key = $this->getKey($location, $lang);
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $resource = $this->resourceLoader->load($location, $lang);
        $this->cache[$key] = $resource;
        return $resource;
    }

    public function exists(string $location, string $lang = ''): bool
    {
        $key = $this->getKey($location, $lang);
        if (isset($this->cache[$key])) {
            return true;
        }
        return $this->resourceLoader->exists($location, $lang);
    }

    private function getKey(string $location, string $lang): string
    {
        return $lang . ':' . $location;
    }
}
