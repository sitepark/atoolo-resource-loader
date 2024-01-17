<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;
use Atoolo\Resource\Exception\RootMissingException;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLoader;

class SiteKitNavigationHierarchyLoader extends SiteKitResourceHierarchyLoader
{
    public function __construct(ResourceLoader $resourceLoader)
    {
        parent::__construct($resourceLoader, 'navigation');
    }

    protected function isRoot(Resource $resource): bool
    {
        return $resource->getData()->getBool('init.home');
    }

    /**
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    protected function loadPrimaryParentResource(Resource $resource): Resource
    {
        $parentLocation = $this->getPrimaryParentLocation($resource);
        if ($parentLocation === null) {
            return $this->loadDefaultRootResource($resource);
        }
        return $this->getResourceLoader()->load($parentLocation);
    }

    /**
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     * @throws RootMissingException
     */
    private function loadDefaultRootResource(Resource $resource): Resource
    {
        $location = $resource->getLocation();
        do {
            $dir = dirname($location);
            if (str_ends_with($location, '/index.php')) {
                $dir = dirname($dir);
            }
            if ($dir === '/' || $dir === '\\') {
                $location = '/index.php';
            } else {
                $location = $dir . '/index.php';
            }
            if ($this->getResourceLoader()->exists($location)) {
                $root = $this->getResourceLoader()->load($location);
                if ($this->isRoot($root)) {
                    return $root;
                }
            }
        } while ($dir !== '/' && $dir !== '\\' && $dir !== '');

        throw new RootMissingException(
            $resource->getLocation(),
            'No default root could be determined for the resource'
        );
    }
}
