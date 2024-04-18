<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/a.php',
    'a',
    'a',
    '',
    \Atoolo\Resource\ResourceLanguage::of('en'),
    new \Atoolo\Resource\DataBag([
        'base' => [
            'trees' => [
                'category' => [
                    'children' => [
                        'b' => [
                            'id' => 'b',
                            'url' => '/b.php'
                        ]
                    ]
                ]
            ]
        ]
    ])
);
