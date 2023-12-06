<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\ResourceBaseLocator;

class DocumentRootLayoutResourceBaseLocator implements ResourceBaseLocator
{
    public function locate(): string
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }
}
