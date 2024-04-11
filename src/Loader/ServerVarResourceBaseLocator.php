<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\ResourceBaseLocator;
use RuntimeException;

class ServerVarResourceBaseLocator implements ResourceBaseLocator
{
    private ?string $resourceBase = null;

    public function __construct(
        private readonly string $variableName
    ) {
    }

    public function locate(): string
    {
        if ($this->resourceBase !== null) {
            return $this->resourceBase;
        }

        if (empty($_SERVER[$this->variableName])) {
            throw new \RuntimeException(
                'missing server variable ' . $this->variableName
            );
        }

        $base = $_SERVER[$this->variableName];
        $baseObjects = $base . DIRECTORY_SEPARATOR . 'objects';
        if (is_dir($baseObjects)) {
            return $this->resourceBase = $baseObjects;
        }
        if (is_dir($base)) {
            return $this->resourceBase = $base;
        }
        throw new RuntimeException(
            'Resource root directory not found: ' . $base
        );
    }
}
