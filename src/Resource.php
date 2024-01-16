<?php

declare(strict_types=1);

namespace Atoolo\Resource;

/**
 * In the Atoolo context, resources are aggregated data from
 * IES (Sitepark's content management system).
 */
class Resource
{
    private readonly DataBag $data;
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly string $location,
        private readonly string $id,
        private readonly string $name,
        private readonly string $objectType,
        array $data,
    ) {
        $this->data = new DataBag($data);
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

    public function getData(): DataBag
    {
        return $this->data;
    }
}
