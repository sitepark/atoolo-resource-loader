<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;
use Atoolo\Resource\ResourceLoader;

class SiteKitResourceHierarchyLoader implements ResourceHierarchyLoader
{
    public function __construct(
        private readonly ResourceLoader $resourceLoader,
        private readonly string $treeName
    ) {
    }

    /**
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function loadRoot(string $location): Resource
    {
        $resource = $this->resourceLoader->load($location);
        while (!$this->isRoot($resource)) {
            $resource = $this->loadPrimaryParentResource($resource);
        }

        return $resource;
    }

    /**
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function loadParent(string $location): ?Resource
    {
        $resource = $this->resourceLoader->load($location);
        if ($this->isRoot($resource)) {
            return null;
        }
        return $this->loadPrimaryParentResource($resource);
    }

    /**
     * @return Resource[]
     * @throws InvalidResourceException if an encountered Resource has no
     * parent but is not considered a root.
     * @throws ResourceNotFoundException
     */
    public function loadPath(string $location): array
    {
        $resource = $this->resourceLoader->load($location);
        $path = [$resource];
        while (!$this->isRoot($resource)) {
            $parent = $this->loadPrimaryParentResource($resource);
            array_unshift($path, $parent);
            $resource = $parent;
        }
        return $path;
    }

    /**
     * @return Resource[]
     *
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function loadChildren(string $location): array
    {
        $resource = $this->resourceLoader->load($location);

        $children = [];
        $childrenLocationList = $this->getChildrenLocationList($resource);
        foreach ($childrenLocationList as $childLocation) {
            $children[] = $this->resourceLoader->load($childLocation);
        }

        return $children;
    }

    /**
     * @throws InvalidResourceException if no primary parent can be found
     * @throws ResourceNotFoundException
     */
    protected function loadPrimaryParentResource(
        Resource $resource
    ): Resource {
        $parentLocation = $this->getPrimaryParentLocation($resource);
        if ($parentLocation === null) {
            throw new InvalidResourceException(
                $resource->getLocation(),
                'the resources should have a parent'
            );
        }
        return $this->resourceLoader->load($parentLocation);
    }

    protected function getResourceLoader(): ResourceLoader
    {
        return $this->resourceLoader;
    }

    protected function isRoot(Resource $resource): bool
    {
        return $this->getPrimaryParentLocation($resource) === null;
    }

    /**
     * @param Resource $resource
     * @return string|null
     * @throws InvalidResourceException
     */
    protected function getPrimaryParentLocation(Resource $resource): ?string
    {
        $parentList = $resource->getData(
            'base.trees.' . $this->treeName . '.parents'
        );

        if (
            $parentList === null ||
            !is_array($parentList) ||
            count($parentList) === 0
        ) {
            return null;
        }

        $firstParent = null;
        foreach ($parentList as $parent) {
            $firstParent ??= $parent;
            $isPrimary = $parent['isPrimary'] ?? false;
            if ($isPrimary === true) {
                if (!isset($parent['url'])) {
                    throw new InvalidResourceException(
                        $resource->getLocation(),
                        'primary parent in ' .
                            'base.trees.' . $this->treeName . '.parents ' .
                            'as no url'
                    );
                }
                if (!is_string($parent['url'])) {
                    throw new InvalidResourceException(
                        $resource->getLocation(),
                        'url of primary parent in ' .
                             'base.trees.' . $this->treeName . '.parents ' .
                             'is not a string'
                    );
                }
                return $parent['url'];
            }
        }

        if (!isset($firstParent['url'])) {
            throw new InvalidResourceException(
                $resource->getLocation(),
                'first parent in ' .
                    'base.trees.' . $this->treeName . '.parents ' .
                    'has no url'
            );
        }

        if (!is_string($firstParent['url'])) {
            throw new InvalidResourceException(
                $resource->getLocation(),
                'url of first parent in ' .
                    'base.trees.' . $this->treeName . '.parents ' .
                    'is not a string'
            );
        }

        return $firstParent['url'];
    }

    /**
     * @return string[]
     */
    protected function getChildrenLocationList(Resource $resource): array
    {
        $childrenList = $resource->getData(
            'base.trees.' . $this->treeName . '.children'
        );

        if (
            $childrenList === null ||
            !is_array($childrenList) ||
            count($childrenList) === 0
        ) {
            return [];
        }

        return array_map(function ($child) {
            return $child['url'];
        }, $childrenList);
    }
}
