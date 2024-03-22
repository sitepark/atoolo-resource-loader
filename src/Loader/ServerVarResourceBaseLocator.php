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

        $resourceLayoutResourceBase = $_SERVER[$this->variableName] .
            '/objects';
        if (is_dir($resourceLayoutResourceBase)) {
            $this->resourceBase = $resourceLayoutResourceBase;
            return $resourceLayoutResourceBase;
        }

        if (is_dir($_SERVER[$this->variableName])) {
            $this->resourceBase = $_SERVER[$this->variableName];
            return $_SERVER[$this->variableName];
        }

        throw new RuntimeException(
            "Resource root directory not found: " .
            $_SERVER[$this->variableName]
        );
    }
}
