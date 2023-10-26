<?php

declare(strict_types=1);

namespace Atoolo\Resource;

class Resource
{
    public function __construct(
        private readonly string $location,
        private readonly string $id,
        private readonly string $name,
        private readonly string $objectType,
        private readonly array $data,
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

    public function getData(string $name): mixed
    {
        return $this->findData($this->data, $name);
    }

    private function findData(array $data, string $name): mixed
    {
        $names = explode('.', $name);
        foreach ($names as $n) {
            if (isset($data[$n])) {
                $data = $data[$n];
            } else {
                return null;
            }
        }
        return $data;
    }
}
