<?php

declare(strict_types=1);

namespace Atoolo\Resource\Exceptions;

/**
 * This exception is used if no resource could be found via the specified
 * location.
 */
class ResourceNotFound extends \RuntimeException
{
    public function __construct(
        private readonly string $location,
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            $location . ': ' . $message,
            $code,
            $previous
        );
    }

    public function getLocation(): string
    {
        return $this->location;
    }
}
