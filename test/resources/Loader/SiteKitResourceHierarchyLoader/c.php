<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/c.php',
    'c',
    'c',
    '',
    \Atoolo\Resource\ResourceLanguage::of('en'),
    new \Atoolo\Resource\DataBag([
        'base' => [
            'trees' => [
                'category' => [
                    'parents' => [
                        'b' => [
                            'isPrimary' => true,
                            'url' => '/b.php'
                        ],
                        'a' => [
                            'url' => '/a.php'
                        ]
                    ]
                ]
            ]
        ]
    ])
);
