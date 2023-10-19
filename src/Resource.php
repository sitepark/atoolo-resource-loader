<?php

declare(strict_types=1);

namespace Atoolo\ResourceLoader;

class Resource
{
    public function __construct(
        private readonly string $location,
        private readonly string $id,
        private readonly string $name,
        private readonly string $objectType,
    ) {
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getObjectType(): string
    {
        return $this->objectType;
    }
}
