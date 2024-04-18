<?php

declare(strict_types=1);

namespace Atoolo\Resource;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;

/**
 * The ResourceLoader interface defines the method used to load resources from a
 * given location.
 */
interface ResourceLoader
{
    /**
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function load(ResourceLocation $location): Resource;

    public function exists(ResourceLocation $location): bool;
}
