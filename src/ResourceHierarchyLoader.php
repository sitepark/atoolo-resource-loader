<?php

declare(strict_types=1);

namespace Atoolo\Resource;

/**
 * The ResourceHierarchyLoader interface defines the method used to load
 * resources or nodes whose hierarchical structure is defined in the resources.
 * For example, the navigation tree.
 */
interface ResourceHierarchyLoader
{
    public function loadRoot(string $location): Resource;

    public function loadParent(string $location): ?Resource;

    /**
     * @return Resource[]
     */
    public function loadPath(string $location): array;

    /**
     * @return Resource[]
     */
    public function loadChildren(string $location): array;
}
