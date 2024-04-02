<?php

declare(strict_types=1);

namespace Atoolo\Resource;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;

/**
 * The ResourceHierarchyLoader interface defines the methods used to load
 * resources or nodes whose hierarchical structure is defined in the resources.
 * For example, the navigation tree.
 */
interface ResourceHierarchyLoader
{
    /**
     * Determines the root resource via the parent links contained in the
     * resource data.
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function loadRoot(string $location): Resource;

    /**
     * Determines the parent resource via the parent links contained in the
     * resource data.
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function loadParent(string $location): ?Resource;

    /**
     * Determines the path to the root resource via the parent links contained
     * in the resource data.
     * The array contains the resources starting with the root resource. The
     * last element of the array is the resource of the passed `$location`
     * @return Resource[]
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function loadPath(string $location): array;

    /**
     * Determines the children resources via the children links contained in the
     * resource data.
     * @return Resource[]
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function loadChildren(string $location): array;

    /**
     * Walks the tree of resources starting from the given location and calls
     * the given function for each resource. Returns the resource where the
     * callable returns true.
     *
     * The callable function expects the following parameters:
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
        callable $fn
    ): ?Resource;
}
