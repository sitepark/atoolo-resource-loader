<?php

declare(strict_types=1);

namespace Atoolo\Resource\Exception;

/**
 * This exception is used when a resource is invalid. This can have the
 * following reasons:
 *
 * - If the resource is syntactically incorrect.
 * - If the resource does not contain necessary data.
 */
class InvalidResourceException extends \RuntimeException
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
