<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader\SiteKit;

class ResourceStub
{
    private array $data = [];

    public function init($data)
    {
        $this->data['init'] = $data;
    }

    public function process(string $name, array $data): void
    {
        $this->data[$name] = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
