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
    public function load(string $location, string $lang = ''): Resource;

    public function exists(string $location, string $lang = ''): bool;
}
