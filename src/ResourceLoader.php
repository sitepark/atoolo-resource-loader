<?php

declare(strict_types=1);

namespace Atoolo\Resource;

interface ResourceLoader
{
    public function load(string $location): Resource;
}
