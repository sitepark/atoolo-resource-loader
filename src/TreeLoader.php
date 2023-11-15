<?php

declare(strict_types=1);

namespace Atoolo\Resource;

/**
 * The TreeLoader interface defines the method used to load resources or nodes
 * whose hierarchical structure is defined in the resources. For example, the
 * navigation tree.
 */
interface TreeLoader
{
    public function loadRootResource(string $location): Resource;
}
