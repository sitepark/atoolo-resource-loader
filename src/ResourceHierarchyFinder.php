<?php

declare(strict_types=1);

namespace Atoolo\Resource;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;

class ResourceHierarchyFinder
{
    public function __construct(
        private readonly ResourceHierarchyLoader $loader
    ) {
    }

    /**
     * Walks the tree of resources starting from the given resource and calls
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
    public function findFirst(Resource|string $base, callable $fn): ?Resource
    {

        $walker = new ResourceHierarchyWalker($this->loader);

        $walker->init($base);

        $current = $walker->getCurrent();

        if ($fn($current) === true) {
            return $current;
        }
        while ($current = $walker->next()) {
            if ($fn($current) === true) {
                return $current;
            }
        }

        return null;
    }
}
