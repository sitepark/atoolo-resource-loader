<?php

declare(strict_types=1);

namespace Atoolo\Resource;

interface ResourceChannelFactory
{
    public function create(): ResourceChannel;
}
