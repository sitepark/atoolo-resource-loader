<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/a.php',
    'a',
    'a',
    '',
    [
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
    ]
);
