<?php

declare(strict_types=1);

namespace Atoolo\Resource;

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
     */
    public function loadRoot(string $location): Resource;

    /**
     * Determines the parent resource via the parent links contained in the
     * resource data.
     */
    public function loadParent(string $location): ?Resource;

    /**
     * Determines the path to the root resource via the parent links contained
     * in the resource data.
     * The array contains the resources starting with the root resource. The
     * last element of the array is the resource of the passed `$location`
     * @return Resource[]
     */
    public function loadPath(string $location): array;

    /**
     * Determines the children resources via the children links contained in the
     * resource data.
     * @return Resource[]
     */
    public function loadChildren(string $location): array;
}
