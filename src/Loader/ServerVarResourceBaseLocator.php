<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\ResourceBaseLocator;

class ServerVarResourceBaseLocator implements ResourceBaseLocator
{
    public function __construct(
        private readonly string $variableName,
        private readonly ?string $subDirectory = null
    ) {
    }

    public function locate(): string
    {
        if (empty($_SERVER[$this->variableName])) {
            throw new \RuntimeException(
                'missing server variable ' . $this->variableName
            );
        }

        if ($this->subDirectory === null) {
            return $_SERVER[$this->variableName];
        }

        return $_SERVER[$this->variableName] . '/' . $this->subDirectory;
    }
}
