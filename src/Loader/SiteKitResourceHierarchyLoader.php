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
    public function loadPrimaryParent(string $location): ?Resource
    {
        $resource = $this->resourceLoader->load($location);
        if ($this->isRoot($resource)) {
            return null;
        }
        return $this->loadPrimaryParentResource($resource);
    }

    /**
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function loadParent(string $location, string $parentId): ?Resource
    {
        $resource = $this->resourceLoader->load($location);
        if ($this->isRoot($resource)) {
            return null;
        }
        return $this->loadParentResource($resource, $parentId);
    }

    /**
     * @return Resource[]
     * @throws InvalidResourceException if an encountered Resource has no
     * parent but is not considered a root.
     * @throws ResourceNotFoundException
     */
    public function loadPrimaryPath(string $location): array
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
        $childrenLocationList = $this->getChildrenLocations($resource);
        foreach ($childrenLocationList as $childLocation) {
            $children[] = $this->resourceLoader->load($childLocation);
        }

        return $children;
    }

    /**
     * Walks the tree of resources starting from the given location and calls
     * the given function for each resource. Returns the resource where the
     * callable returns true.
     *
     * The callable function expects the following parameter:
     * - Resource: the current resource
     *
     * The callable function should return true if the current resource is the
     * one we are looking for.
     *
     * @param callable(Resource): bool $fn
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function findRecursive(
        string $location,
        callable $fn,
    ): ?Resource {

        $resource = $this->resourceLoader->load($location);

        if ($fn($resource) === true) {
            return $resource;
        }

        $childrenLocationList = $this->getChildrenLocations($resource);
        foreach ($childrenLocationList as $childLocation) {
            $result = $this->findRecursive(
                $childLocation,
                $fn
            );
            if ($result !== null) {
                return $result;
            }
        }

        return null;
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

    protected function loadParentResource(
        Resource $resource,
        string $parentId
    ): ?Resource {
        $parentLocation = $this->getParentLocation($resource, $parentId);
        if ($parentLocation === null) {
            return null;
        }
        return $this->resourceLoader->load($parentLocation);
    }

    public function getResourceLoader(): ResourceLoader
    {
        return $this->resourceLoader;
    }

    public function isRoot(Resource $resource): bool
    {
        $parentList = $resource->getData()->getAssociativeArray(
            'base.trees.' . $this->treeName . '.parents'
        );

        return count($parentList) === 0;
    }

    /**
     * @param Resource $resource
     * @return string|null
     * @throws InvalidResourceException
     */
    public function getPrimaryParentLocation(Resource $resource): ?string
    {
        $parentList = $resource->getData()->getAssociativeArray(
            'base.trees.' . $this->treeName . '.parents'
        );

        if (
            count($parentList) === 0
        ) {
            return null;
        }

        $firstParent = null;
        foreach ($parentList as $parent) {
            if (!is_array($parent)) {
                throw new InvalidResourceException(
                    $resource->getLocation(),
                    'primary parent in ' .
                    'base.trees.' . $this->treeName . '.parents ' .
                    'is invalid'
                );
            }
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

    public function getParentLocation(
        Resource $resource,
        string $parentId
    ): ?string {
        $parentList = $resource->getData()->getAssociativeArray(
            'base.trees.' . $this->treeName . '.parents'
        );

        if (
            count($parentList) === 0
        ) {
            return null;
        }

        foreach ($parentList as $id => $parent) {
            if (!is_array($parent)) {
                throw new InvalidResourceException(
                    $resource->getLocation(),
                    'parent in ' .
                    'base.trees.' . $this->treeName . '.parents ' .
                    'not an array'
                );
            }
            if ($parentId === (string)$id) {
                return $parent['url'];
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getChildrenLocations(Resource $resource): array
    {
        $childrenList = $resource->getData()->getAssociativeArray(
            'base.trees.' . $this->treeName . '.children'
        );

        if (
            count($childrenList) === 0
        ) {
            return [];
        }

        return array_map(function ($child) use ($resource) {
            if (!is_array($child)) {
                throw new InvalidResourceException(
                    $resource->getLocation(),
                    'children in ' .
                    'base.trees.' . $this->treeName . '.children ' .
                    'not an array'
                );
            }
            return $child['url'];
        }, $childrenList);
    }
}
