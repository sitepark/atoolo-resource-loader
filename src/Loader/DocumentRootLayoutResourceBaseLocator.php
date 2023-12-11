<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\ResourceBaseLocator;

class DocumentRootLayoutResourceBaseLocator implements ResourceBaseLocator
{
    public function locate(): string
    {
        if (empty($_SERVER['DOCUMENT_ROOT'])) {
            throw new \RuntimeException(
                'missing server variable DOCUMENT_ROOT'
            );
        }
        return $_SERVER['DOCUMENT_ROOT'];
    }
}
