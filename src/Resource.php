<?php

declare(strict_types=1);

namespace Atoolo\Resource;

/**
 * In the Atoolo context, resources are aggregated data from
 * IES (Sitepark's content management system).
 */
class Resource
{
    /**
     * @param array<string, mixed> $data
     */
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

    /**
     * @param string $name Name of the value that can be returned from the
     * data object. The name can contain dots (`.`) in case of a nested
     * structure and define the corresponding levels.
     *
     * Example:
     * [
     *   'foo' : [
     *     'bar' : 'value'
     *   ]
     * ]
     *
     * For the above structure, `value` can be retrieved using the following
     * `$name`: `boo.bar`
     *
     * @return mixed
     */
    public function getData(string $name): mixed
    {
        return $this->findData($this->data, $name);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function findData(array $data, string $name): mixed
    {
        $names = explode('.', $name);
        foreach ($names as $n) {
            if (is_array($data) && isset($data[$n])) {
                $data = $data[$n];
            } else {
                return null;
            }
        }
        return $data;
    }
}
