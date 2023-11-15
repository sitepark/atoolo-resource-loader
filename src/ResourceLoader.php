<?php

declare(strict_types=1);

namespace Atoolo\Resource;

/**
 * The ResourceLoader interface defines the method used to load resources from a
 * given location.
 */
interface ResourceLoader
{
    public function load(string $location): Resource;

    public function exists(string $location): bool;
}
