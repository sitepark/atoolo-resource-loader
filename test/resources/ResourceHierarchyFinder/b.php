<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/b.php',
    'b',
    'b',
    '',
    \Atoolo\Resource\ResourceLanguage::of('en'),
    new \Atoolo\Resource\DataBag([
        'base' => [
            'trees' => [
                'category' => [
                    'parents' => [
                        'a' => [
                            'url' => '/a.php'
                        ]
                    ],
                    'children' => [
                        'c' => [
                            'id' => 'c',
                            'url' => '/c.php'
                        ]
                    ]
                ]
            ]
        ]
    ])
);
