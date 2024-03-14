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
     * Walks the tree of resources starting from the given location and calls
     * the given function for each resource. Returns the resource where the
     * callable returns true.
     *
     * The callable function expects the following parameters:
     * - array of Resource: the path to the current resource.
     *   Does not contain the current resource
     * - Resource: the current resource
     *
     * The callable function should return true if the current resource is the
     * one we are looking for.
     *
     * @param callable(Resource[], Resource): bool $fn
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function findRecursive(
        string $location,
        callable $fn,
    ): ?Resource {
        return $this->findRecursiveInternal($location, $fn, []);
    }

    /**
     * @param Resource[] $parentPath
     */
    private function findRecursiveInternal(
        string $location,
        callable $fn,
        array $parentPath
    ): ?Resource {

        $resource = $this->resourceLoader->load($location);

        if ($fn($parentPath, $resource) === true) {
            return $resource;
        }

        $childrenLocationList = $this->getChildrenLocationList($resource);
        foreach ($childrenLocationList as $childLocation) {
            $parentPathForChild = array_merge($parentPath, [$resource]);
            $result = $this->findRecursiveInternal(
                $childLocation,
                $fn,
                $parentPathForChild
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

    /**
     * @return string[]
     */
    protected function getChildrenLocationList(Resource $resource): array
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
