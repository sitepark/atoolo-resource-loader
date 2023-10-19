<?php

declare(strict_types=1);

namespace Atoolo\ResourceLoader;

interface ResourceLoader
{
    public function load(string $location): Resource;
}
